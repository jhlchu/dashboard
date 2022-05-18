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
		init() {
			this.show_suggestions  = false,
			this.taxes             = [],
			this.customer_list     = [],
			this.cart              = old_cart,
			this.invoice_discount  = old_invoice_discount ? old_invoice_discount : '',
			this.shipping_handling = old_shipping_handling ? old_shipping_handling : 0,
			this.invoice_cart      = old_cart ? old_cart : '',
			this.new_cart_row      = {},
			this.current_customer  = {
				name       : old_name ? old_name             : '',
				address    : old_address ? old_address       : '',
				country    : old_country ? old_country       : '',
				email      : old_email ? old_email           : '',
				phone      : old_phone ? old_phone           : '',
				province   : old_province ? old_province     : '',
				tax_region : old_tax_region ? old_tax_region : '',
			}
			if (old_tax_region) {
				this.taxes = taxes.find(tax => tax.id === parseInt(old_tax_region)).tax;
				console.log('t',this.taxes);
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
			console.log('response', response);
			this.customer_list = response.status === 200 && await response.json();
			this.show_suggestions = (Array.isArray(this.customer_list) && this.customer_list.length > 0) ? true : false;
		},

		selectCustomer(id) {
			this.current_customer = this.customer_list.find(customer => customer.id === id);
			this.show_suggestions = false;
			document.querySelector('#tax_region').selectedIndex = this.current_customer.tax_region - 1;
			this.taxes = taxes.find(tax => tax.id === this.current_customer.tax_region).tax;
			console.log('tax', this.taxes);
			/* this.searchTax({target : {value : this.current_customer.tax_region}}); */
		},

		unselect() { this.show_suggestions = false; },

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