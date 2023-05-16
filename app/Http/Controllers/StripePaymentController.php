<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stripe\Exception\CardException;
use Stripe\StripeClient;

class StripePaymentController extends Controller
{
    private StripeClient $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(env("STRIPE_SECRET"));
    }
    public function charge (Request $request): JsonResponse
    {
        $validator = Validator::make($request->only("number", "exp_month", "exp_year", "cvc"), [
            'number' => 'required',
            'exp_month' => 'required',
            'exp_year' => 'required',
            'cvc' => 'required',
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json($validator->errors()->first());
        }
        $token = $this->createToken($request->only("number", "exp_month", "exp_year", "cvc"));
        if (!empty($token["error"])) {
            return response()->json($token["error"]);
        }
        if (empty($token['id'])) {
            return response()->json("Failed. (ID)");
        }
        $payment = Payment::create([
            "user_id" => Auth::user()->id,
            "token_id" => $token->id,
            "card_number" => $request->number,
            "amount" => $request->amount,
            "status" => "pending",
        ]);
        $charge = $this->createCharge($token['id'], $request->amount);
        if (!empty($charge) && $charge['status'] == 'succeeded') {
            $user = User::find(Auth::user()->id);
            $balance = (User::select("balance")->where("id", Auth::user()->id)->get())[0]['balance'];
            $user->update(["balance" => $balance + $request->amount]);
            $payment->update(["status" => "success"]);
            return response()->json(true);
        } else {
            $payment->update(["status" => "failed"]);
            return response()->json(false);
        }
    }

    private function createToken ($card): array|\Stripe\Token
    {
        $token = null;
        try {
            $token = $this->stripe->tokens->create([
                "card" => [
                    "number" => $card["number"],
                    "exp_month" => $card["exp_month"],
                    "exp_year" => $card["exp_year"],
                    "cvc" => $card["cvc"]
                ],
            ]);
        } catch (CardException $e) {
            $token["error"] = $e->getMessage();
        }
        return $token;
    }
    private function createCharge($tokenId, $amount): array|\Stripe\Charge
    {
        $charge = null;
        try {
            $charge = $this->stripe->charges->create([
                'amount' => $amount * 100,
                'currency' => 'usd',
                'source' => $tokenId,
                'description' => 'My first payment'
            ]);
        } catch (Exception $e) {
            $charge['error'] = $e->getMessage();
        }
        return $charge;
    }
}
