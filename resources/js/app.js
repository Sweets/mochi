//require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';

Vue.use(VueRouter);

import App from '../views/App.vue';
import Index from '../views/Index.vue';

import Thread from '../views/Thread.vue';
import Post from '../views/Post.vue';
import Profile from '../views/Profile.vue';

const router = new VueRouter({
	mode: 'history',
	routes: [
		{
			path: '/',
			name: 'index',
			component: Index
		},

		{
			path: '/user/:id',
			name: 'profile',
			component: Profile
		},
		{
			path: '/thread/:id',
			name: 'thread',
			component: Thread
		},
		{
			path: '/post/:id',
			name: 'post',
			component: Post
		},
	]
});

const app = new Vue({
	el: '#vue',
	components:  { App },
	router
});
