<template>
    <div>
    <button :class="classes" v-text="text" @click="subscribe"></button>
    </div>
</template>

<script>
export default {
    props: ['active'],

    data() {
        return {
            subscribed: this.active
        };
    },

    computed: {
        text(){
            return this.subscribed ? 'Unsubscribe' : 'Subscribe';
        },
        classes() {
            return this.subscribed ? 'btn-primary' : 'btn-light';
        }
    },

    methods: {
        subscribe() {

            axios[(this.subscribed ? 'delete' : 'post')](location.pathname + '/subscriptions');

            this.subscribed = !this.subscribed;
        }
    }
}
</script>

<style>

</style>