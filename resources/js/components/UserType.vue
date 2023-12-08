<template>
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>  New User ? Register Yourself </strong>  
            </div>
            <div class="panel-body">
                <form @submit.prevent="validate" method="post" role="form">
                    <div class="form-group">
                        <label for="user_type">Who are you?</label>
                        <select name="user_type" id="user_type" class="form-control" v-model="user_type">
                            <option value="">-- Select --</option>
                            <option :value="val.id" v-for="(val, key) in user_types" :key="key">{{ val.name }}</option>
                        </select>            
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger next" :disabled="!user_type">Validate &amp; Next &LongRightArrow;</button>
                        <hr />
                        Already Registered ?  <a href="/advertiser/login" >Login here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'usertype',
        data() {
          return {
            user_type: '',
            user_types: [],
          }
        },
        created() {
            this.$nextTick(function() {
                axios.get('/usertypes').then((result) => {
                    if (result.data.status) {
                        this.user_types = result.data.success;
                    }
                }).catch(err => console.log(err));
            })
        },
        mounted() {
        },
        methods: {
            validate() {
                if (parseInt(this.user_type) === 1) {
                    this.$router.push({name: 'corporate', params: { selected_opt: this.user_type }});
                }
                else {
                    this.$router.push({name: 'individual', params: { selected_opt: this.user_type }});
                }
            }
        }
    }
</script>

<style scope>
    .card-body {
        font-weight: bold;
    }
</style>