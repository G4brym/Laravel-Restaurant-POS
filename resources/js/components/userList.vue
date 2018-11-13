<template>
    <table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Age</th>
            <th>Department</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="user in users"  :key="user.id" :class="{active: activeUser === user}">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.age }}</td>
            <td>{{ user.department }}</td>
            <td>
                <a class="btn btn-sm btn-primary" v-on:click.prevent="editUser(user)">Edit</a>
                <a class="btn btn-sm btn-danger" v-on:click.prevent="deleteUser(user)">Delete</a>
                <a class="btn btn-sm btn-primary" v-on:click.prevent="definePlayer(1, user)">P1</a>
                <a class="btn btn-sm btn-primary" v-on:click.prevent="definePlayer(2, user)">P2</a>
            </td>
        </tr>
    </tbody>
</table>
</template>
<script>
module.exports = {
  props: ["users"],
  data: function() {
    return {
      activeUser: {}
    };
  },
  methods: {
    editUser: function(user) {
      this.activeUser = user;
      this.$emit("edit-user", user);
    },
    deleteUser: function(user) {
      this.$emit("delete-user", user);
    },
    definePlayer: function(playerNumber, user) {
      Vue.set(this.$root, "player" + playerNumber, user.name);
    }
  }
};
</script>
