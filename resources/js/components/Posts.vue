<template>
    <div class="posts">
        <div class="posts-header">
            <button class="btn" @click="show_modal_create=true">Create post</button>
        </div>
        <div id="posts" class="posts-body">
            <Post
                v-for="post in posts.posts"
                :post="post"
            />
        </div>
    </div>
    <div class="modal" v-if="show_modal_create">
        <div class="modal-mask">
            <div class="modal-container">
                <input v-model="title" type="text" name="title">
                <textarea v-model="text" name="title"></textarea>
                <button class="btn" @click="create">Create</button>
                <button class="btn" @click="show_modal_create=false">Close</button>
                <p>{{error}}</p>
            </div>
        </div>
    </div>
</template>

<script>
import Post from "./Post.vue"
import {useRouter} from "vue-router"
import {mapState} from "vuex"
import Swal from "sweetalert2"

export default {
    components: {Post},
    data() {
        return {
            router: useRouter(),
            show_modal_create: false,
            title: '',
            text: '',
            error: '',
        }
    },
    computed: mapState(['auth', 'posts']),
    methods: {
        create: async function () {
            await axios.post('/post/create', {title: this.title, text: this.text})
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
                        this.title = ''
                        this.text = ''
                        this.error = ''
                        this.show_modal_create = false
                        this.$store.dispatch('posts')
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            text: 'Post created!',
                            confirmButtonText: 'Close',
                        })
                    }
                })
        }
    },
    mounted() { this.$store.dispatch('posts') }
}
</script>
