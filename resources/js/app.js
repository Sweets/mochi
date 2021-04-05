//require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import App from '../views/App.vue';
import Index from '../views/Index.vue';
import Post from '../views/Post.vue';

const router = new VueRouter({
	mode: 'history',
	routes: [
		{
			path: '/',
			name: 'index',
			component: Index
		},
		{
			path: '/post/:id',
			name: 'post',
			component: Post
		}
	]
});

const app = new Vue({
	el: '#app',
	components:  { App },
	router
});
