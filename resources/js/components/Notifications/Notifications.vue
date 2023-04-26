<template>
    <div>
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="far fa-comments"></i> 
                <div v-if="notifications.length > 0">
                    <span class="badge badge-danger navbar-badge">{{notifications.length}}</span>
                </div>                
            </a>

            <div v-if="notifications.length > 0">
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <notification
                        v-for="notification in notifications"
                        :key="notification.id"
                        :notification="notification">
                    </notification>                 
                    <a class="dropdown-item" href="#" @click.prevent="markAllAsRead">
                        Limpar Notificações
                    </a>                               
                </div>
            </div>
        </li>        
    </div>
</template>

<style>
    .dropdown-toggle::after {
        display: none;
    }
</style>

<script>
export default {
    created () {
        this.$store.dispatch('loadNotifications')
    },
    computed: {
        notifications () {

            if(this.$store.state.notifications.items.length > 0){
                this.showAlert();
            }
            
            return this.$store.state.notifications.items
        }
    },
    methods: {
        markAllAsRead () {
            this.$store.dispatch('markAllAsRead')
        },
        showAlert() {
            this.$swal("Você tem Mensagens!!!");
        },
    }    
}
</script>