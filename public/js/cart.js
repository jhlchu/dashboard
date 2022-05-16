const STORAGE_KEY = "cart-alpinejs";
const carttorage = {
	fetch() {
		const cart = JSON.parse(localStorage.getItem(STORAGE_KEY) || "[]");
		cart.forEach((cart_row, index) => {
			cart_row.id = index;
		});
		carttorage.uid = cart.length;
		return cart;
	},
	save(cart) {
		localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
	}
};

const twoDigitFloat = (num) => parseFloat(parseFloat(num).toFixed(2));
const twoDigitString = (num) => parseFloat(num).toFixed(2);
const money = (num) => '$' + twoDigitString(num);


let len = 0;

document.addEventListener("alpine:init", () => {
	Alpine.data("invoice", () => ({

		show_suggestions : false,
		taxes            : [],
		customer_list    : [],
		current_customer : {
			name       : '',
			address    : '',
			country    : '',
			email      : '',
			phone      : '',
			province   : '',
			tax_region : 0
		},

		init() {
			this.show_suggestions = false,
			this.taxes            = [],
			this.customer_list    = [],
			this.current_customer = {
				name       : '',
				address    : '',
				country    : '',
				email      : '',
				phone      : '',
				province   : '',
				tax_region : 0
			}
		},

		async searchTax({target: {value : region_id}}) {
			const response = await fetch('/api/tax', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
				},
				body: JSON.stringify({'id': region_id})
			});
			this.taxes = await response.json();
		},

		async searchCustomers({target: {value : name}}) {
			if (!name) {return }
			const response = await fetch('/api/customer', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
				},
				body: JSON.stringify({'name': name})
			});
			this.customer_list = response.status === 200 && await response.json();

			if (Array.isArray(this.customer_list) && this.customer_list.length > 0) {
				this.show_suggestions = true;
			} else { this.show_suggestions = false; }
		},

		selectCustomer(id) {
			this.current_customer = this.customer_list.find(customer => customer.id === id);
			this.show_suggestions = false;
			document.querySelector('#tax_region').selectedIndex = this.current_customer.tax_region - 1;
			this.searchTax({target : {value : this.current_customer.tax_region}});
		},

		unselect() { this.show_suggestions = false; },

		cart             : [],
		invoice_discount         : '',
		shipping_handling : 0,
		invoice_cart     : '',
		new_cart_row     : {},

		updateJson() { this.invoice_cart = JSON.stringify(this.cart); },

		addCartRow() {
			let {description, price, discount, quantity} = this.new_cart_row;
			if (!description || !price || !quantity) {return ;}

			this.cart.push({
				id: len++,
				description,
				price,
				discount,
				quantity
			});
			this.new_cart_row = {};
			document.querySelector('#input_description').focus();
			this.invoice_cart = JSON.stringify(this.cart);
		},


		calculateTotal(price = 0.00, discount, quantity = 0) {
			if (!discount || discount === 0) { return price * quantity; }
			return twoDigitFloat(this.calculateDiscount(price, discount) * quantity) || 0;
		},

		calculateDiscount(price, discount = '$0') {
			let discount_value = parseFloat(discount.replace(/^\D|,+/g, ''));
			if (discount.includes('%')) {
				return twoDigitFloat(price * (1 - (discount_value)/100)) || 0;
			} else { return twoDigitFloat(price - discount_value) || 0; }
		},

		grossTotal() {
			return twoDigitFloat(this.cart.reduce((acc, {price, discount, quantity}) => acc += this.calculateDiscount(price, discount) * quantity, 0));
		},

		beforeTax() {
			//return this.grossTotal() + this.shipping_handling;
			return twoDigitFloat(this.calculateDiscount((this.grossTotal() + this.shipping_handling), this.invoice_discount));
		},

		taxTotal() {
			return twoDigitFloat(this.taxes.reduce((acc, {value}) => acc += value, 0));
		},

		netTotal() {
			return twoDigitFloat(this.beforeTax() * (1 + this.taxTotal()));
		},

		removeCartRow(cart_row) {
			this.cart.splice(this.cart.indexOf(cart_row), 1);
			this.invoice_cart = JSON.stringify(this.cart);
		},
	}));
});