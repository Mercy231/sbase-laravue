<template>
    <div class="comment">
        <div class="comment-header">
            <p>{{comment.user.full_name}}</p>
        </div>
        <div class="comment-body">
            <p>{{ comment.text }}</p>
            <div v-if="comment.user.id === auth.user.id">
                <button @click="this.show_modal_update=true">Update</button>
                <button @click="this.delete">Delete</button>
            </div>
        </div>
        <hr>
        <div class="comment-footer">
            <h5>Comments:</h5>
        </div>
        <div class="comments">

        </div>
    </div>

    <!--Comment update modal-->
    <div class="modal" v-if="show_modal_update">
        <div class="modal-mask">
            <div class="modal-container">
                <h1>update comment</h1>
                <textarea v-model="text" name="title"></textarea>
                <button class="btn" @click="update">Update</button>
                <button class="btn" @click="show_modal_update=false">Close</button>
                <p>{{error}}</p>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex";

export default {
    data() {
        return {
            text: this.comment.text,
            show_modal_update: false,
            error: '',
        }
    },
    computed: mapState(['auth']),
    props: ['comment'],
    methods: {
        update: async function () {
            await axios.patch(`/comment/${this.comment.id}`, {text: this.text})
                .then(response => {
                    if (typeof response.data === 'string') {
                        this.error = response.data
                    } else {
                        this.error = ''
                        this.show_modal_update = false
                        this.$store.dispatch('posts')
                    }
                })
        },
        delete: async function () {
            if (confirm("Delete comment?")) {
                await axios.delete(`/comment/${this.comment.id}`)
                    .then(response => {this.$store.dispatch('posts')})
            }
        },
    }
}
</script>
