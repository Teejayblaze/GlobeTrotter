export function Corporate(obj) {

    this.user_type = obj.user_type ? obj.user_type : null;

    this.name = obj.name ? obj.name : null;

    this.website = obj.website ? obj.website : null;
    
    this.email = obj.email ? obj.email : null;
    
    this.phone = obj.phone ? obj.phone : null;
    
    this.address = obj.address ? obj.address : null;
    
    this.rc_number = obj.rc_number ? obj.rc_number : null;

    this.termsandconditions = obj.termsandconditions ? obj.termsandconditions : false;

    this.admin_firstname = obj.admin_firstname ? obj.admin_firstname : null;

    this.admin_lastname = obj.admin_lastname ? obj.admin_lastname : null;

    this.admin_email = obj.admin_email ? obj.admin_email : null;

    this.admin_phone = obj.admin_phone ? obj.admin_phone : null;

    this.admin_flag = obj.admin_flag ? obj.admin_flag : true;

    this.adminpassword = obj.adminpassword ? obj.adminpassword : null;
    
    this.adminpassword_confirmation = obj.adminpassword_confirmation ? obj.adminpassword_confirmation : null;

    this.auth_staff = obj.auth_staff ? obj.auth_staff : null;
}

export function validateCorporateInformation(form, type="Corporate") {

    this.valid = true;
    this.validation_errors = [];
    this.auth_staff_data = [];
    self = this;

    form.find('.corporate .required').each(function(idn, el) {
        let elm = $(el);
        if (!elm.val().trim()) {
            self.validation_errors.push('<strong>' + elm.data('value') + '</strong> field should not be empty.');
            self.valid = false;
        }

        if (elm.attr('id') === 'website' && elm.val().trim()) {
            let regex = /^http(s)?\:\/\/www\.[A-za-z\-\_]+\.[A-Za-z]+$/i;
            if (!regex.test(elm.val().trim())) {
                self.validation_errors.push('<strong>' + elm.data('value') + '</strong> is not valid.');  
                self.valid = false;
            }
        }

        if (elm.attr('id') === 'email' && elm.val().trim()) {

            let website = form.find('#website').val();
            let webdomain = website.substr(website.indexOf('.')+1, website.length);
            let email = elm.val().trim();
            let maildomain = email.split('@')[1];

            if (webdomain !== maildomain) {
                self.validation_errors.push('<strong>' + elm.data('value') + '</strong> domain does not match the website domain hence the provided <strong>'+ type +' email address</strong> is not valid.');  
                self.valid = false;
            }
        }

        if (elm.attr('id') === 'admin_password_confirmation' && elm.val().trim()) {
            let password = form.find('#admin_password').val();
            let conf_password = elm.val().trim();
            if (password !== conf_password) {
                self.validation_errors.push('<strong>' + elm.data('value') + '</strong> and <strong>Administrator Password</strong> field do not match.');  
                self.valid = false;
            }
        }

        if (elm.attr('id') === 'termsandconditions') {
            if (!elm.is(':checked')) {
                self.validation_errors.push('<strong>' + elm.data('value') + '</strong> field should be accepted.');  
                self.valid = false;
            }
        }
    });

    if ( form.find('.auth_staff').children().length ) {
        
        let auth_staff_det = {}
        let MAX_STAFF_FIELD = 5;
        let count = 0;

        form.find('.auth_staff .staff_required').each(function(idn, el) {
            
            let elm = $(el);
            
            if (!elm.val().trim()) {
                self.validation_errors.push('<strong>' + elm.data('value') + '</strong> field should not be empty.');
                self.valid = false;
            }

            count++

            if (count > MAX_STAFF_FIELD) { // Reset the number cycle so, we could have a new dict (object)
                count = 1;
                auth_staff_det = {};
            }

            if (count <= MAX_STAFF_FIELD) auth_staff_det[elm.attr('name')] = elm.val().trim(); 
            
            if (count === MAX_STAFF_FIELD) self.auth_staff_data.push(auth_staff_det);
        });
    }
    
    return {
        valid: this.valid,
        validation_errors: this.validation_errors,
        auth_staff_data: this.auth_staff_data
    }
}

export function authStaffTemplate() {

    this.staffTemplate = function(staff_counter){
        return  `
            <div class="staff-info-holder">
                <div class="col-md-12">
                    <div class="login-header custom-login-header mt-10">
                        <p><strong>${staff_counter}<sup>${ this.inferSuperScript(staff_counter) }</sup> - Staff Information</strong></p>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Staff Firstname<i class="far fa-user"></i></label>
                            <input type="text" placeholder="Adam" name="corp_indv_fname_${staff_counter}" class="staff_required form-control" data-value="Staff Firstname">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Staff Lastname<i class="far fa-user"></i></label>
                            <input type="text" placeholder="Kowalsky" name="corp_indv_lname_${staff_counter}" class="staff_required form-control" data-value="Staff Lastname">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Staff Email<i class="far fa-envelope-open"></i></label>
                            <input type="text" placeholder="staffmail@domain.com" name="corp_indv_email_${staff_counter}" class="staff_required form-control" data-value="Staff Email">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Staff Password<i class="far fa-user-lock"></i></label>
                            <input type="text" placeholder="mypassword123" name="corp_indv_password_${staff_counter}" class="staff_required form-control" data-value="Staff Password">
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Staff Designation <i class="far fa-user-tie"></i></label>
                        <input type="text" placeholder="Financial Secretary" name="corp_indv_designation_${staff_counter}" class="staff_required form-control" data-value="Staff Designation">
                    </div>
                </div>
            </div>

        `;
    }

    this.inferSuperScript = function(num) {
        let superScript = ['st', 'nd', 'rd', 'th'];                
        switch(num) {
            case 1:
                return superScript[0];
                case 2: 
                    return superScript[1];
                    case 3: 
                        return superScript[2];
                        default:
                            return superScript[3];
        }
    }
}

export function show_sweetalert(title, text, type) {
    return window.swal({
        title: title,
        type: type,
        text: text
    });
}