<template>
    <div class="login-form individual-login-form wow slideInLeft" data-wow-duration="2.5s" data-wow-delay=".25s">
        <div class="row">
            <div class="col-md-12">
                <div class="login-header custom-login-header">
                    <p><strong>Your Personal Information</strong></p>
                    <hr>
                </div>
                <div class="alert alert-danger" v-if="errors.length">
                    <ul>
                        <li v-for="(error, index) in errors" v-html="error" :key="index"></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-12">
                <form  @submit.prevent="save_individual" method="post" role="form">
                    <div class="row individual">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstname">Firstname <i class="far fa-user"></i></label>
                                <input type="text" placeholder="John" class="form-control required" name="firstname" id="firstname" v-model="individual.firstname" data-value="Firstname">
                                <span class="text text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastname">Lastname <i class="far fa-user"></i></label>
                                <input type="text" placeholder="Doe" class="form-control required" name="lastname" id="lastname" v-model="individual.lastname" data-value="Lastname">
                                <span class="text text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address">Address <i class="far fa-street-view"></i></label>
                                <input type="text" class="form-control required" placeholder="12, Ribadu Road, Off Awolowo way, Ikoyi" name="address" id="address" v-model="individual.address" data-value="Address">
                                <span class="text text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone <i class="far fa-phone"></i></label>
                                <input type="tel" class="required form-control" placeholder="+234-813-2614337" name="phone" id="phone" v-model="individual.phone" data-value="Phone Number">
                                <span class="text text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email <i class="far fa-envelope"></i></label>
                                <input type="email" class="required form-control" placeholder="yourmail@domain.com" name="email" id="email" v-model="individual.email" data-value="Email Address">
                                <span class="text text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="password">Password <i class="far fa-lock-alt"></i></label>
                                <input type="password" name="password" class="required form-control" placeholder="*********" id="password" v-model="individual.password" data-value="Password">
                                <span class="text text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password <i class="far fa-lock-alt"></i></label>
                                <input type="password" name="password_confirmation" placeholder="*********" id="password_confirmation" class="required form-control" v-model="individual.password_confirmation" data-value="Confirm Password">
                                <span class="text text-danger"></span>
                            </div>
                        </div>

                        <div class="col-sm-12 tnc-holder"></div>
                        
                        <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <div class="individual-btn-div text-right">
                                <button type="submit" class="theme-btn individual-btn action-button btn no-shdow-btn color-bg save">
                                    <i class="far fa-check"></i>
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

<!-- :disabled="processing" -->

<script>
    import { 
        Individual, 
        validateIndividualInformation,
        show_sweetalert
    } from '../models/individual';

    export default {
        name: 'individual',
        computed: {},
        data() {
            return {
                individual: new Individual({}),
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
            this.individual.user_type = 2;
        },
        methods: {
            save_individual(evt) {
                this.errors = Object.assign([],[]);
                let validator = new validateIndividualInformation($(evt.target));
                console.log("form submitted ", validator, $(evt.target));
                if ( validator.valid ) {

                    this.processing = true;

                    axios.post('/signup', this.individual).then((res) => {

                        if (!res.data.status) {
                            show_sweetalert(
                                'Notice', 
                                'Apologies, Our server has encoutered an error due to your provided information kindly check the at the top.', 
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

                    }).catch(ex => { this.processing = false; });
                }
            }
        }
    }
</script>