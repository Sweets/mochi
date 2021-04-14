<template>
	<div>
		<header>
			<template v-if="logged_in == false">
				<a href="javascript:;" v-on:click="show_login_modal">Login</a>
				<a href="javascript:;" v-on:click="show_registration_modal">Register</a>
			</template>
			<template v-else>
				Hello, User! <a href="javascript:;" v-on:click="user_logout">Logout</a>
			</template>
		</header>
		<router-view />
		<footer>
			&copy; MMXXI
		</footer>
	</div>
</template>
<script>
	import axios from 'axios';
	import Swal from 'sweetalert2';

	import { Modal } from '../js/modal.js';
	import Login from '../views/modals/Login.vue';
	import Register from '../views/modals/Register.vue';

    export default {
		data: function() {
			return {
				get logged_in() {
					return localStorage.getItem('ApiToken') != null;
				},
				RegistrationModal: null,
				LoginModal: null
			};
		},
		methods: {
			show_registration_modal: function() {
				let context = this;
				Modal(this, Register).fire(function(Component) {
					Component.submit_registration(function() {
						context.$forceUpdate();
					});
				});
			},
			show_login_modal: function() {
				let context = this;
				Modal(this, Login).fire(function(Component) {
					Component.submit_login(function() {
						context.$forceUpdate();
					});
				});
			},
			user_logout: function() {
				localStorage.removeItem('ApiToken');
				this.$forceUpdate();
			}
		},
		mounted: function() {
/*			let _vm = this.$options._base;
			this.RegistrationModal = _vm.extend.apply(_vm, [Register]);
			this.LoginModal = _vm.extend.apply(_vm, [Login]);*/
		}
	};
</script>
