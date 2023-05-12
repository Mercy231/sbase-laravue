<template>
    <div class="post">
        <div class="post-header">
            <p>{{post.user.full_name}}</p>
            <h3>{{post.title}}</h3>
        </div>
        <div class="post-body">
            <p>{{post.text}}</p>
            <div v-if="post.user.id === auth.user.id">
                <button @click="show_modal_update=true">Update</button>
                <button @click="this.delete">Delete</button>
            </div>
        </div>
        <hr>
        <div class="post-footer">
            <h5>Comments:</h5>
            <button @click="show_modal_create=true">Create comment</button>
        </div>
        <div class="comments">
            <Comment
                v-for="comment in post.comments"
                :comment="comment"
            />
        </div>
    </div>

    <!--Post update modal-->
    <div class="modal" v-if="show_modal_update">
        <div class="modal-mask">
            <div class="modal-container">
                <h1>update post</h1>
                <input v-model="title" type="text" name="title">
                <textarea v-model="text" name="title"></textarea>
                <button class="btn" @click="update">Update</button>
                <button class="btn" @click="show_modal_update=false">Close</button>
                <p>{{error}}</p>
            </div>
        </div>
    </div>

    <!--Comment create modal-->
    <div class="modal" v-if="show_modal_create">
        <div class="modal-mask">
            <div class="modal-container">
                <h1>create comment</h1>
                <textarea v-model="comment_text" name="title"></textarea>
                <button class="btn" @click="create">Create</button>
                <button class="btn" @click="show_modal_create=false">Close</button>
                <p>{{error}}</p>
            </div>
        </div>
    </div>
</template>

<script>
import Comment from "./Comment.vue"
import {mapState} from "vuex";
import Swal from "sweetalert2";

export default {
    components: {Comment},
    data() {
        return {
            title: this.post.title,
            text: this.post.text,
            comment_text: '',
            error: '',
            show_modal_update: false,
            show_modal_create: false,
        }
    },
    computed: mapState(['auth']),
    props: ['post'],
    methods: {
        create: async function () {
            await axios.post('/comment/create', {text: this.comment_text, post_id: this.post.id})
                .then(response => {
                    if (typeof response.data === 'string') {
                        // this.error = response.data
                        Swal.fire({
                            title: 'Error!',
                            icon: 'error',
                            text: response.data,
                            confirmButtonText: 'Close',
                        })
                    } else {
                        this.error = ''
                        this.show_modal_create = false
                        this.$store.dispatch('posts')
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            confirmButtonText: 'Close',
                        })
                    }
                })
        },
        update: async function () {
            await axios.patch(`/post/${this.post.id}`, {title: this.title, text: this.text})
                .then(response => {
                    if (typeof response.data === 'string') {
                        // this.error = response.data
                        Swal.fire({
                            title: 'Error!',
                            icon: 'error',
                            text: response.data,
                            confirmButtonText: 'Close',
                        })
                    } else {
                        this.error = ''
                        this.show_modal_update = false
                        this.$store.dispatch('posts')
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            confirmButtonText: 'Close',
                        })
                    }
                })
        },
        delete: async function () {
            if (confirm("Delete post?")) {
                await axios.delete(`/post/${this.post.id}`)
                    .then(response => {
                        this.$store.dispatch('posts')
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            confirmButtonText: 'Close',
                        })
                    })
            }
        },
    }
}
</script>
