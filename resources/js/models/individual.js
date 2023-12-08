export function Individual(obj) {

    this.user_type = obj.user_type ? obj.user_type : null;

    this.firstname = obj.firstname ? obj.firstname : null;

    this.lastname = obj.lastname ? obj.lastname : null;
    
    this.email = obj.email ? obj.email : null;
    
    this.phone = obj.phone ? obj.phone : null;
    
    this.address = obj.address ? obj.address : null;
    
    this.bvn = obj.bvn ? obj.bvn : null;
    
    this.password = obj.password ? obj.password : null;
    
    this.password_confirmation = obj.password_confirmation ? obj.password_confirmation : null;

    this.termsandconditions = obj.termsandconditions ? obj.termsandconditions : false;
}

export function validateIndividualInformation(form) {

    this.valid = true;
    self = this;

    form.find('.individual .required, .checkboxes').each(function(idn, el) {
        let elm = $(el);
        console.log("elements = ", elm, elm.next('span.text'));
        let target = elm.next('span.text');
        target.html('');

        if (!elm.val().trim()) {
            target.html('<strong>' + elm.data('value') + '</strong> field should not be empty.');
            self.valid = false;
        }

        if (elm.attr('id') === 'email' && elm.val().trim()) {
            if (!validate_email(elm.val().trim())) {
                target.html('<strong>' + elm.data('value') + '</strong> field should not be empty.');
                self.valid = false;  
            }
        }

        if (elm.attr('id') === 'password_confirmation' && elm.val().trim()) {
            let password = form.find('#password').val();
            let conf_password = elm.val().trim();
            if (password !== conf_password) {
                target.html('<strong>' + elm.data('value') + '</strong> and <strong>Password</strong> field do not match.');  
                self.valid = false;
            }
        }

        
        if (elm.attr('id') === 'termsandconditions') {
            if (!elm.is(':checked')) {
                elm.closest('.filter-tags').find('span.text').html('<strong>' + elm.data('value') + '</strong> field should be accepted.');  
                self.valid = false;
            }
            else {
                elm.closest('.filter-tags').find('span.text').html('');
            }
        }
    });
    
    return {
        valid: this.valid
    }
}

export function show_sweetalert(title, text, type) {
    return window.swal({
        title: title,
        type: type,
        text: text
    });
}

function validate_email(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}