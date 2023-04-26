<template>
    <div>
        <a href="#" class="dropdown-item">
            <div class="media">
                <img v-bind:src="user.avatar" v-bind:alt="user.name" class="img-size-50 mr-3 img-circle">
                <div class="media-body">
                <h3 class="dropdown-item-title">{{ user.name  }}</h3>
                <p class="text-sm" @click.prevent="markAsRead(notification.id)">{{ comment.titulo  }}</p>
                <p class="text-sm text-muted"> {{ notification.created_at | formatDate }}</p>
                </div>
            </div>
        </a>               
    </div>
</template>

<script>
export default {
    props: ['notification'],
    computed: {
        comment() {
            return this.notification.data.assunto
        },
        link() {
            return this.notification.data.link
        },
        user() {
            return this.notification.data.user
        }
    },
    methods: {
        markAsRead (idNotification) {
            this.$store.dispatch('markAsRead', {id: idNotification});
            window.location.href = this.notification.data.link;
        }
    }
}
</script>