<template>
  <div :id="'reply-'+id" class="card">
    <div class="card-header" :class="isBest ? 'text-light bg-success':''">
      <div class="level">
        <h6 class="flex">
          <a :href="'/profiles/'+reply.owner.name" v-text="reply.owner.name" :class="isBest ? 'text-warning':''"></a>
          said <span v-text="ago"></span>
        </h6>

        <div v-if="signedIn">
            <favorite :reply = "reply"></favorite>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div v-if="editing">
        <form v-on:submit.prevent="update" >
          <div class="form-group">
            <wysiwyg v-model="body"></wysiwyg>
          <!-- <textarea class="form-control" v-model="body" required></textarea> -->
          </div>

          <button type="submit" class="btn-primary btn-xs mr-1">Update</button>
          <button class="btn btn-link" @click="resetForm">Cancel</button>
        </form>
      </div>

      <div v-else v-html="body"></div>
    </div>

    <div class="card-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
      <div v-if="authorize('owns', reply)">
        <button class="btn-primary btn-sm mr-1" @click="editing = true">Edit</button>
        <button class="btn-danger btn-sm mr-1" @click="destory">Delete</button>
      </div>

      <button class="btn-primary btn-sm ml-a" @click="markBestReply" v-if="authorize('owns', reply.thread)">Best Reply?</button>
    </div>
  </div>
</template>

<script>
import Favorite from './Favorite.vue';
import moment from 'moment'

export default {
  props: [ 'reply' ],

  components: { Favorite },

  data() {
    return {
      editing: false,
      id: this.reply.id,
      body: this.reply.body,
      isBest: this.reply.isBest
    };
  },

  computed: {
    ago() {
      return moment(this.reply.created_at).fromNow() + '...';
    }
  },

  created() {
    window.events.$on('best-reply-selected', id => {
      this.isBest = (id === this.id);
    });
  },

  methods: {
    update() {
      console.log(this.id);
      axios.patch(
        "/replies/" + this.id, {
        body: this.body
      })
      .catch(error => {
        flash(error.response.data, 'danger');
      });

      this.editing = false;

      flash("Updated!");
    },

    destory() {
      axios.delete("/replies/" + this.id);

      this.$emit('deleted', this.id);

    },

    markBestReply() {
      axios.post('/replies/' + this.id + '/best');
      window.events.$emit('best-reply-selected', this.id);
    },

    resetForm() {
      this.body = this.reply.body;
      this.editing = false;
    }
  }
};
</script>

<style>
</style>