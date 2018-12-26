<template>
    <li class="dropdown notifications-menu" ref="notifMenu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" @click="resetCounter">
            <i class="fa fa-bell-o"></i>
            <span class="label label-danger" v-show="notifNum">{{ notifNum > 10 ? "10+" : notifNum }}</span>
        </a>
        <ul class="dropdown-menu">
            <li class="header">You have {{ notifNum === 0 ? "no" : notifNum }} new notifications</li>
            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu" ref="notifItems">
                    <template v-for="notif in notifications">
                        <notification-item :classes="notif.classes"
                                           :href="notif.href"
                                           :text="notif.text">
                        </notification-item>
                    </template>
                    <!--<li>
                        <a href="#">
                            <i class="fa fa-users text-aqua"></i> 5 new members joined today
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                            page and may cause design problems
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-users text-red"></i> 5 new members joined
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-user text-red"></i> You changed your username
                        </a>
                    </li>-->
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
                if (notifications.length === 15) {
                    notifications.pop();
                }
                notifications.unshift({'text': text, 'classes': classes, 'href': href});

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
