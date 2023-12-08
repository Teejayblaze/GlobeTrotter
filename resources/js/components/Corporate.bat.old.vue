<template>
    <section class="color-bg parallax-section">
        <div class="city-bg"></div>
        <div class="cloud-anim cloud-anim-bottom x1"><i class="fal fa-cloud"></i></div>
        <div class="cloud-anim cloud-anim-top x2"><i class="fal fa-cloud"></i></div>
        <div class="overlay op1 color3-bg"></div>
        <br>
        <br>
        <div class="container">
            <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                <br/> 
                <form @submit.prevent="save_corporate" method="post">                            
                    <div class="corporate">
                        <div class="list-single-main-item fl-wrap hidden-section tr-sec" style="box-shadow: 0px 0px 0px 4px rgba(0,0,0,0.2);">
                            <div class="profile-edit-container">
                                <div class="custom-form">
                                    <fieldset class="fl-wrap">
                                        <div class="list-single-main-item-title fl-wrap">
                                            <h3>Your Corporate Information</h3>
                                        </div>
                                        <div class="alert alert-danger" v-if="errors.length">
                                            <ul>
                                                <li v-for="(error, index) in errors" v-html="error" :key="index"></li>
                                            </ul>
                                        </div>
                                        <div>
                                            <div class="col-sm-12">
                                                <label>Name <i class="fal fa-user-alt"></i> </label>
                                                <input type="text" placeholder="Your Corporate Name" name="name" id="name" class="required" v-model="corporate.name" data-value="Corporate Name">                                                  
                                            </div>
                                        </div>
                                        <div>
                                            <div class="col-sm-6">
                                                <label>Website <i class="far fa-globe-asia"></i></label>
                                                <input type="text" placeholder="Your Website" name="website" id="website" class="required" v-model="corporate.website" data-value="Corporate Website">                                                  
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Email Address<i class="far fa-envelope"></i>  </label>
                                                <input type="text" placeholder="yourmail@domain.com" name="email" id="email" class="required" v-model="corporate.email" data-value="Corporate Email">                                                  
                                            </div>
                                        </div>
                                        <div>
                                            <div class="col-sm-12">
                                                <label>Address <i class="fal fa-street-view"></i> </label>
                                                <input type="text" placeholder="Your Address" name="address" id="address" class="required" v-model="corporate.address" data-value="Corporate Address">                                                  
                                            </div>
                                        </div>
                                        <div>
                                            <div class="col-sm-6">
                                                <label>Phone<i class="far fa-phone"></i>  </label>
                                                <input type="text" placeholder="87945612233" name="phone" id="phone" class="required" v-model="corporate.phone" data-value="Corporate Phone">
                                            </div>
                                            <div class="col-sm-6">
                                                <label>RC Number <i class="far fa-file-signature"></i>  </label>
                                                <input type="text" placeholder="RC012356" name="rc_number" id="rc_number" class="required" v-model="corporate.rc_number" data-value="Corporate RC Number">                                                  
                                            </div>
                                        </div>
                                        

                                        <div class="col-sm-12">
                                            <div class="filter-tags">
                                                <input type="checkbox" name="termsandconditions" id="termsandconditions" class="required" v-model="corporate.termsandconditions" data-value="Terms and Conditions">
                                                <label for="termsandconditions">By continuing, you agree to the<a href="/terms-and-conditions" target="_blank">Terms and Conditions</a> on this platform.</label>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>Select Logo</label> 
                                                    <div class="add-list-media-wrap">
                                                        <form class="fuzone">
                                                            <div class="fu-text">
                                                                <span><i class="fal fa-image"></i> Click here or drop files to upload</span>
                                                            </div>
                                                            <input type="file" class="upload">
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <label>Logo Preview</label> 
                                                            <div class="add-list-media-wrap add-preview"></div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label>Start Upload</label> 
                                                            <div class="add-list-media-wrap"></div>
                                                            <a href="#" class="btn no-shdow-btn action-button color-bg">Upload <i class="far fa-upload"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <br />

                                        <div class="admin-section">
                                            <div class="list-single-main-item-title fl-wrap">
                                                <h3>Administrator Section</h3>
                                            </div> 
                                            <div>
                                                <div class="col-sm-6">
                                                    <label>Firstname<i class="far fa-user"></i></label>
                                                    <input type="text" placeholder="Bob" name="admin_firstname" class="required" v-model="corporate.admin_firstname" data-value="Administrator Firstname">                                                  
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>Lastname<i class="far fa-user"></i></label>
                                                    <input type="text" placeholder="Karmel" name="admin_lastname" class="required" v-model="corporate.admin_lastname" data-value="Administrator Lastname"> 
                                                </div>
                                            </div>
                                            <div>
                                                <div class="col-sm-6">
                                                    <label>Email<i class="far fa-envelope-open"></i></label>
                                                    <input type="text" placeholder="adminmail@domain.com" name="admin_email" class="required" v-model="corporate.admin_email" data-value="Administrator Email">                                                  
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>Phone<i class="far fa-phone"></i></label>
                                                    <input type="text" placeholder="2348123338773" name="admin_phone" class="required" v-model="corporate.admin_phone" data-value="Administrator Phone Number"> 
                                                </div>
                                            </div>
                                            <div>
                                                <div class="col-sm-12">
                                                    <label>Password <i class="fal fa-user-lock"></i></label>
                                                    <input type="password" placeholder="Your Password" name="admin_password" id="admin_password" class="required" v-model="corporate.adminpassword" data-value="Administrator Password">
                                                </div>
                                            </div>
                                            <div>
                                                <div class="col-sm-12">
                                                    <label>Confirm Password <i class="fal fa-user-lock"></i></label>
                                                    <input type="password" placeholder="Your Password Confirmation" name="admin_password_confirmation" id="admin_password_confirmation" class="required" v-model="corporate.adminpassword_confirmation" data-value="Administrator Confirm Password">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="filter-tags">
                                                    <input type="checkbox" name="admin_flag" id="admin_flag" class="required" v-model="corporate.admin_flag" data-value="Aministrator Checkbox" :readonly="true" :disabled="true">
                                                    <label for="admin_flag">Corporate Administrator</label>
                                                </div>
                                            </div>
                                        </div>

                                        <br/>
                                        
                                        <div class="col-sm-12">
                                            <div class="list-single-main-item-title fl-wrap">
                                                <h3>Authorized Staff</h3>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="col-sm-4">
                                                <a href="#" class="previous-form action-button btn color2-bg no-shdow-btn add_auth_staff" @click.prevent="addStaffs">Add Staff(s)<i class="fal fa-user-plus"></i></a>
                                            </div>
                                            <div class="col-sm-8">
                                                <p><small>You can add authorized staff that should have access to your dashboard and perform task on your behalf.</small></p>
                                            </div>
                                        </div>

                                        <div class="auth_staff">

                                        </div>

                                        <span class="fw-separator"></span>
                                        <button type="submit" class="action-button btn no-shdow-btn color-bg save" :disabled="processing">
                                            <i class="fal fa-check"></i>
                                            <span v-if="!processing"> REGISTER</span>
                                            <span v-if="processing">Processing...</span>
                                        </button>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
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
            //     return 'You are a <strong>Corporate</strong> body!';
            // },
        },
        data() {
            return {
                corporate: new Corporate({}),
                staff_counter: 0,
                errors: [],
                processing: false,
            }
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
                let validator = new validateCorporateInformation($(evt.target));
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
<style scope>
    .text-danger {
        font-size: 12px !important;
    }
    .save span {
        color: #FFF !important;
    }
    .action-button {
        float: right;
    }

    .previous-form {
        float:left;
    }

    .parallax-section {
        padding: 110px 0 !important;
    }

    .alert {
        clear: both;
        margin-bottom: 40px;
    }

    .alert ul {
        display: block;
        padding: 15px 31px;
    }

    .alert ul li {
        font-size: 13px;
        line-height: 24px;
        padding-bottom: 10px;
        font-weight: 500;
        color: #b80640;
        text-align: left;
    }
</style>