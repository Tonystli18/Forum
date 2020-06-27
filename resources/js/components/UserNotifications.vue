<template>
  <li class="nav-item dropdown" v-if="notifications.length">
    <a class="nav-link dropdown-toggle" href="#"
      data-toggle="dropdown"
    >
    <i class="fa fa-bell"></i>
    </a>

    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
      <li v-for="(notification, id) in notifications" :key="id">
        <a :href="notification.data.link" 
          class="dropdown-item" 
          v-text="notification.data.message"
          @click="markAsRead(notification)"> {{notification.data.message}}</a>
      </li>
    </ul>
  </li>
</template>

<script>
export default {
    data() {
        return {
            notifications: false
        }
    },

    created() {
      axios.get('/profiles/' + window.App.user.name + '/notifications')
          .then(response => this.notifications = response.data);
    },

    methods: {
      markAsRead(notification) {
          let path = '/profiles/' + window.App.user.name + '/notifications/' + notification.id;
          axios.delete(path);
          location.reload();
      }
    }
}
</script>

<style>
</style>