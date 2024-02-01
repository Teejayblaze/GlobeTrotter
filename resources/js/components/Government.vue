<template>
    <div class="login-form individual-login-form wow slideInLeft" data-wow-duration="2.5s" data-wow-delay=".25s">
        <div class="row">
            <div class="col-md-12">
                <div class="login-header custom-login-header">
                    <p><strong>Your Government Information</strong></p>
                    <hr>
                </div>
                <div class="alert alert-danger" v-if="errors.length">
                    <ul>
                        <li v-for="(error, index) in errors" v-html="error" :key="index"></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-12">
                <form @submit.prevent="save_corporate" method="post">
                    <div class="row corporate">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Your Government Name <i class="far fa-user"></i> </label>
                                <input type="text" placeholder="Multichoice plc" name="name" id="name" class="required form-control" v-model="corporate.name" data-value="Government Name">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Website <i class="far fa-globe"></i></label>
                                <input type="text" placeholder="http://www.multichoice.com" name="website" id="website" class="required form-control" v-model="corporate.website" data-value="Government Website">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Email Address <i class="far fa-envelope"></i>  </label>
                                <input type="text" placeholder="j.morre@multichoice.com" name="email" id="email" class="required form-control" v-model="corporate.email" data-value="Government Email">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Address <i class="far fa-street-view"></i> </label>
                                <input type="text" placeholder="Your Address" name="address" id="address" class="required form-control" v-model="corporate.address" data-value="Government Address">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Phone<i class="far fa-phone"></i>  </label>
                                    <input type="text" placeholder="87945612233" name="phone" id="phone" class="required form-control" v-model="corporate.phone" data-value="Available Phone Number">
                                </div>
                            </div>
                            <input type="hidden" placeholder="RC012356" name="rc_number" id="rc_number" class="form-control" v-model="corporate.rc_number" value="Government" data-value="Government RC Number">
                        </div>
                        <div class="col-sm-12 tnc-holder corporate"></div>

                        <div class="col-md-12">
                            <div class="login-header custom-login-header mt-10">
                                <p><strong>Administrator Section</strong></p>
                                <hr>
                            </div>
                        </div>
                        <div class="admin-section">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Firstname<i class="far fa-user"></i></label>
                                        <input type="text" placeholder="Bob" name="admin_firstname" class="required form-control" v-model="corporate.admin_firstname" data-value="Administrator Firstname">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Lastname<i class="far fa-user"></i></label>
                                        <input type="text" placeholder="Karmel" name="admin_lastname" class="required form-control" v-model="corporate.admin_lastname" data-value="Administrator Lastname">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Email<i class="far fa-envelope-open"></i></label>
                                        <input type="text" placeholder="adminmail@domain.com" name="admin_email" class="required form-control" v-model="corporate.admin_email" data-value="Administrator Email">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone<i class="far fa-phone"></i></label>
                                        <input type="text" placeholder="2348123338773" name="admin_phone" class="required form-control" v-model="corporate.admin_phone" data-value="Administrator Phone Number">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Password <i class="fal fa-user-lock"></i></label>
                                    <input type="password" placeholder="Your Password" name="admin_password" id="admin_password" class="required form-control" v-model="corporate.adminpassword" data-value="Administrator Password">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Confirm Password <i class="fal fa-user-lock"></i></label>
                                    <input type="password" placeholder="Your Password Confirmation" name="admin_password_confirmation" id="admin_password_confirmation" class="required form-control" v-model="corporate.adminpassword_confirmation" data-value="Administrator Confirm Password">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="filter-tags">
                                    <div class="form-group">
                                        <input type="checkbox" name="admin_flag" id="admin_flag" class="required" v-model="corporate.admin_flag" data-value="Aministrator Checkbox" :readonly="true" :disabled="true">
                                        <label for="admin_flag">Government Administrator</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="login-header custom-login-header mt-20">
                                <p><strong>Authorized Staff</strong></p>
                                <hr>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="individual-btn-div">
                                <a id="add-staff-btn" @click.prevent="addStaffs" type="submit" class="theme-btn individual-btn add_auth_staff"><i class="far fa-user-plus"></i> Add Staff(s)</a>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group add-staff">
                                <p>You can add authorized staff that should have access to your dashboard and perform task on your behalf.</p>
                            </div>
                        </div>

                        <div class="auth_staff"></div>

                        <div class="col-md-12">
                            <div class="individual-btn-div text-right">
                                <button type="submit" class="theme-btn individual-btn action-button btn no-shdow-btn color-bg save" :disabled="processing">
                                    <i class="fal fa-check"></i>
                                    <span v-if="!processing"> Register</span>
                                    <span v-if="processing">Processing...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import { 
        Corporate, 
        validateCorporateInformation, 
        authStaffTemplate,
        show_sweetalert
    } from '../models/corporate';

    export default {
        name: 'corporate',
        computed: {
            // filterOption() {
            //     return 'You are a <strong>Government</strong> body!';
            // },
        },
        data() {
            return {
                corporate: new Corporate({
                    rc_number: 'Government',
                }),
                staff_counter: 0,
                errors: [],
                processing: false,
            }
        },
        beforeMount() {
            window.axios.get('/terms-and-conditions').then(res => {
                if (res.data) {
                    $('.tnc-holder').html(res.data.tnc);
                }
            })
        },
        mounted() {
            this.corporate.user_type = 1;
        },
        methods: {
            addStaffs(evt) {
                this.staff_counter += 1;
                let MAX_STAFF = 5;

                if ( this.staff_counter <= MAX_STAFF ) {
                    let staff_template_form = new authStaffTemplate().staffTemplate(this.staff_counter);
                    $(evt.target).closest('form').find('.auth_staff').append(staff_template_form);
                    // $('html,body').animate({ scrollTop: $(document).height() }, 500);
                    return;
                }
                else {
                    this.errors = Object.assign([],[]);
                    this.errors.push(`Sorry, You are only allowed to add a maximum of ${MAX_STAFF} authorized staff on the platform.`);
                }
            },
            save_corporate(evt) {
                this.errors = Object.assign([],[]);
                let validator = new validateCorporateInformation($(evt.target), "Government");
                this.errors = validator.validation_errors;

                if (this.errors.length) $('html,body').animate({ scrollTop: 0 }, 500);

                if ( validator.valid ) {

                    this.corporate.auth_staff = validator.auth_staff_data;
                    this.processing = true;

                    axios.post('/signup', this.corporate).then((res) => {
                        if (!res.data.status) {
                            show_sweetalert(
                                'Notice', 
                                'Apologies, Our server has encountered an error due to your provided information kindly check the hint at the top.', 
                                'error'
                            );
                            for( let err in res.data.errors ) {
                                this.errors.push(res.data.errors[err].join(''));
                                $('html,body').animate({ scrollTop: 0 }, 500);
                            }
                            this.processing = false;
                        }
                        else {
                            show_sweetalert(
                                'Notice', 
                                res.data.success, 
                                'success'
                            ).then(function(){
                                window.location.href = encodeURI(res.data.link);
                            });
                        }

                    }).catch(ex => { this.processing = false; });;
                }
            }
        }
    }
</script>