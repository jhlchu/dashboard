const bcrypt = dcodeIO.bcrypt;
const username_field       = document.querySelector('#form_modal_name');
const user_id_field = document.querySelector('#form_user_id');
const password_field       = document.querySelector('#form_password');
const invoice_number_field = document.querySelector('#form_invoice_number');
const form_body            = document.querySelector('#form_body');
const form_error           = document.querySelector('#form_error');
const submit_button        = document.querySelector('#hash_submit');


const open_form_modal = (id, username, route, invoice_number) => {
	
	//Reset
	submit_button.innerHTML  = 'Verify';
	submit_button.disabled   = false;
	form_error.style.display = 'none';
	password_field.value     = '';
	
	//Passing parameters
	password_field.focus();
	username_field.textContent = username;
	user_id_field.value=id;
	invoice_number_field.value = invoice_number;
	form_body.action           = route;
}

const verify_hash = () => {
	form_error.style.display = 'none';
	console.log(hashes);

	const form_user = hashes.filter(({id}) => id === parseInt(user_id_field.value)).pop();
	const managers = hashes.filter(({is_manager}) => is_manager == true);
	
	//User not found
	if (!form_user) {
		form_error.style.display = 'block'; 
		return;
	}

	const cmp = [...managers, form_user].reduce((acc, idx) => acc = acc || bcrypt.compareSync(password_field.value, idx.password), false);
	/* const cmp = bcrypt.compareSync(password_field.value, form_user.password); */

	if (cmp) {
		submit_button.innerHTML = 'Loading...';
		submit_button.disabled  = true;
		form_body.submit();
	} else { form_error.style.display = 'block'; return ;}
}

document.querySelector('#form_password').addEventListener('keydown', e => {
	if (e.which === 13 || e.keyCode === 13) {
		e.preventDefault();
		verify_hash();
	};
});

submit_button.addEventListener('click', e => {
	e.preventDefault();
	verify_hash();
});