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
                <br />
                <form @submit.prevent="save_individual" method="post" role="form">
                    <div class="individual">
                        <div class="list-single-main-item fl-wrap hidden-section tr-sec" style="box-shadow: 0px 0px 0px 4px rgba(0,0,0,0.2);">
                            <div class="profile-edit-container">
                                <div class="custom-form">
                                    <fieldset class="fl-wrap">
                                        <div class="list-single-main-item-title fl-wrap">
                                            <h3>Your Personal Information</h3>
                                        </div>
                                        <div class="alert alert-danger" v-if="errors.length">
                                            <ul>
                                                <li v-for="(error, index) in errors" v-html="error" :key="index"></li>
                                            </ul>
                                        </div>
                                        <div>
                                            <div class="col-sm-6">
                                                <label for="firstname">Firstname <i class="fal fa-user-alt"></i></label>
                                                <input type="text" placeholder="John"  name="firstname" id="firstname" class="required" v-model="individual.firstname" data-value="Firstname">
                                                <span class="text text-danger"></span>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="lastname" class="form-label">Lastname <i class="fal fa-user-alt"></i></label>
                                                <input type="text" placeholder="Doe" name="lastname" id="lastname" class="required" v-model="individual.lastname" data-value="Lastname">
                                                <span class="text text-danger"></span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="col-sm-12">
                                                <label for="address">Address <i class="fal fa-street-view"></i></label>
                                                <input type="text" placeholder="Your Address" name="address" id="address" class="required" v-model="individual.address" data-value="Address">
                                                <span class="text text-danger"></span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="col-sm-6">
                                                <label for="phone">Phone <i class="far fa-phone"></i></label>
                                                <input type="text" placeholder="+23487945612233" name="phone" id="phone" class="required" v-model="individual.phone" data-value="Phone Number">
                                                <span class="text text-danger"></span>
                                            </div>
                        
                                            <div class="col-sm-6">
                                                <label for="email">Email <i class="far fa-envelope"></i></label>
                                                <input type="text" placeholder="yourmail@domain.com" name="email" id="email" class="required" v-model="individual.email" data-value="Email Address">
                                                <span class="text text-danger"></span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="col-sm-12">
                                                <label for="password">Password <i class="fal fa-lock-alt"></i></label>
                                                <input type="password" name="password" placeholder="*********" id="password" class="required" v-model="individual.password" data-value="Password">
                                                <span class="text text-danger"></span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="col-sm-12">
                                                <label for="password_confirmation">Confirm Password <i class="fal fa-lock-alt"></i></label>
                                                <input type="password" name="password_confirmation" placeholder="*********" id="password_confirmation" class="required" v-model="individual.password_confirmation" data-value="Confirm Password">
                                                <span class="text text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 tnc-holder">
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

<style scope>
    .text-danger {
        font-size: 12px !important;
        color:#b80640;
        float: left;
    }

    .save span {
        color: #FFF !important;
    }

     .action-button {
        float: right;
    }

    .custom-form input {
        margin-bottom: 4px !important;
    }

    .col-sm-6, .col-sm-12 {
        margin-bottom: 15px;
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

    .terms-wrapper {
        overflow-y: scroll;
        height: 200px;
        border: solid thin gray;
        padding: 10px;
        margin-bottom: 1em;
        text-align: justify;
    }

    .terms-heading {
        font-weight: bold;
    }

    .terms-wrapper p{
        font-size: 9pt;
        font-weight: normal;
        line-height: 1.6;
        margin-bottom: 1.25rem;
        text-rendering: optimizeLegibility;
        color: #000 !important;
    }

    ul.terms {
        list-style-type: none;
        margin-left: 1.1rem;
        font-family: inherit;
        font-size: 1rem;
        line-height: 1.6;
        list-style-position: outside;
        margin-bottom: 1.25rem;
        font-weight: normal;
    }

    ul.terms li {
        margin-bottom: 10px;
        font-size: 9pt;
    }

    .terms-subheading {
        text-decoration: underline;
    }
</style>