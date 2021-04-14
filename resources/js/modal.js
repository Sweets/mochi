import Swal from 'sweetalert2';

class _Modal {
	constructor(scope, Component) {
		this._vm = scope.$root.$options._base;
		this.component = this._vm.extend.apply(this._vm, [Component]);
	}

	fire(callback) {
		let scope = this;
		Swal.fire({
			html: '<div id="vue_component__modal"></div>',
			willOpen: function() {
				scope.component = (new scope.component()).$mount('#vue_component__modal');
			},
			preConfirm: function() {
				callback(scope.component);
			}
		})
	}
};

let Modal = function(scope, Component) {
	return new _Modal(scope, Component);
};

export { Modal };
