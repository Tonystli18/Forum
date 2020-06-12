<template>
    <button type="submit" :class="classes" @click="toggle">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
        <span v-text="count"></span>
    </button>
</template>

<script>
export default {
    props: ['reply'],

    data() {
        return {
            count: this.reply.favoritesCount,
            isFavorited: this.reply.isFavorited
        }
    },

    computed: {
        classes() {
            return ['btn-sm', this.isFavorited? 'btn-primary btn-sm':'btn-secondary btn-sm']
        },
        endpoint() {
            return '/replies/' + this.reply.id +'/favorites';
        }
    },

    methods: {
        toggle() {
            this.isFavorited? this.destory() : this.create();
        },

        create(){
            axios.post(this.endpoint);
            this.isFavorited = true;
            this.count++;
        },

        destory() {
            axios.delete(this.endpoint);
            this.isFavorited = false;
            this.count--;
        }
    }

}
</script>

<style>

</style>