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
            <button @click="show_modal_create=true">Create comment</button>
        </div>
        <div class="comments">
            <Comment
                v-for="comment in comment.comments"
                :comment="comment"
            />
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
import {mapState} from "vuex"
import Swal from "sweetalert2"

export default {
    data() {
        return {
            text: this.comment.text,
            comment_text: '',
            show_modal_update: false,
            error: '',
            show_modal_create: false,
        }
    },
    computed: mapState(['auth']),
    props: ['comment'],
    methods: {
        create: async function () {
            await axios.post('/comment/create', {
                text: this.comment_text,
                comment_id: this.comment.id,
            })
                .then(response => {
                    if (typeof response.data === 'string') {
                        return Promise.reject(response.data)
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
                }).catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        icon: 'error',
                        text: error.data,
                        confirmButtonText: 'Close',
                    })
                })
        },
        update: async function () {
            await axios.patch(`/comment/${this.comment.id}`, {text: this.text})
                .then(response => {
                    if (typeof response.data === 'string') {
                        return Promise.reject(response.data)
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
                }).catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        icon: 'error',
                        text: error.data,
                        confirmButtonText: 'Close',
                    })
                })
        },
        delete: async function () {
            if (confirm("Delete comment?")) {
                await axios.delete(`/comment/${this.comment.id}`)
                    .then(response => {
                        if (typeof response.data === 'string') {
                            return Promise.reject(response.data)
                        }
                        this.$store.dispatch('posts')
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            confirmButtonText: 'Close',
                        })
                    }).catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            icon: 'error',
                            text: error.data,
                            confirmButtonText: 'Close',
                        })
                    })
            }
        },
    }
}
</script>
