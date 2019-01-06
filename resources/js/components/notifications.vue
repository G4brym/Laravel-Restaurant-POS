<template>
    <li class="dropdown notifications-menu" ref="notifMenu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" @click="resetCounter">
            <i class="fa fa-bell-o"></i>
            <span class="label label-danger" v-show="notifNum">{{ notifNum > 10 ? "10+" : notifNum }}</span>
        </a>
        <ul class="dropdown-menu">
            <li class="header" v-if="notifications.length === 0">You have no notifications</li>
            <li v-else>
                <!-- inner menu: contains the actual data -->
                <ul class="menu" ref="notifItems">
                    <template v-for="notif in notifications">
                        <notification-item :classes="notif.classes"
                                           :href="notif.href"
                                           :text="notif.text">
                        </notification-item>
                    </template>
                </ul>
            </li>
        </ul>
    </li>
</template>

<script>
    import notifItem from "./notificationItem.vue"
    export default {
        components: {
            'notification-item': notifItem
        },
        data: function() {
            return {
                notifications: [],
                notifNum: 0,
            }
        },
        methods: {
            addNotif: function(text, classes, href) {
                if (this.notifications.length === 15) {
                    this.notifications.pop();
                }
                this.notifications.unshift({'text': text, 'classes': classes, 'href': href});

                if (!this.$refs.notifMenu.classList.contains("open")) {
                    this.notifNum++;
                }
            },
            resetCounter: function() {
                this.notifNum = 0;
            }
        }
    }
</script>

<style scoped>
    .dropdown-menu {
        width: 500px !important;
    }
</style>
