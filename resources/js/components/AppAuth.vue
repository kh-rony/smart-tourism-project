<template>
    <div v-if="active" :style="stylesection" class="loginSignup">
        <div class="loginSignup-content">
            <div class="card card-signup" data-background-color="orange">
                <div class="header mx-auto">
                    <h4 class="title">Continue with</h4>
                    <div class="social-line">
                        <a href="/auth/facebook"
                           class="btn btn-neutral btn-facebook btn-icon btn-round">
                            <i class="fa fa-facebook-square"></i>
                        </a>
                        <a href="/auth/google"
                           class="btn btn-neutral btn-google btn-icon btn-round">
                            <i class="fa fa-google"></i>
                        </a>
                    </div>
                </div>
                <form v-if="login" @submit.prevent="onLogin">
                    <div class="header mx-auto">Already have an account</div>
                    <div class="card-body">
                        <div class="input-group form-group-no-border">
                        <span class="input-group-addon">
                            <i class="fa fa-envelope"></i>
                        </span>
                            <input type="email" class="form-control" placeholder="Email..." v-model="email" required>
                        </div>
                        <p v-if="errors.email">
                            <span v-for="error in errors.email">{{error}}<br></span>
                        </p>
                        <div class="input-group form-group-no-border" v-if="!passwordForgetRequest">
                        <span class="input-group-addon">
                            <i class="fa fa-lock"></i>
                        </span>
                            <input type="password" class="form-control" placeholder="Password..." v-model="password"
                                   required>
                        </div>
                        <div class="input-group form-group-no-border" v-if="passwordReset">
                        <span class="input-group-addon">
                            <i class="fa fa-lock"></i>
                        </span>
                            <input type="password" class="form-control" placeholder="Confirm Password..."
                                   v-model="password_confirmation" required>
                        </div>
                        <p v-if="errors.password">
                            <span v-for="error in errors.password">{{error}}<br></span>
                        </p>
                        <a class="white--text title-small" v-if="passwordForget" @click.prevent="onPasswordForget">Forget
                            Password?</a>
                    </div>
                    <div class="mx-auto py-3">
                        <button type="submit" class="btn btn-neutral btn-round btn-lg" :disabled="loading">
                            <i v-if="loading" class="fa fa-spinner"></i>
                            <span v-else>{{loginButtonText}}</span>
                        </button>
                    </div>
                </form>
                <form v-else @submit.prevent="onSignUp">
                    <div class="header mx-auto">Create a new account</div>
                    <div class="card-body">
                        <div class="input-group form-group-no-border">
                        <span class="input-group-addon">
                            <i class="fa fa-text-width"></i>
                        </span>
                            <input type="text" class="form-control" placeholder="First Name..." v-model="firstName"
                                   required>
                        </div>
                        <p v-if="errors.first_name">
                            <span v-for="error in errors.first_name">{{error}}<br></span>
                        </p>
                        <div class="input-group form-group-no-border">
                        <span class="input-group-addon">
                            <i class="fa fa-text-width"></i>
                        </span>
                            <input type="text" class="form-control" placeholder="Last Name..." v-model="lastName"
                                   required>
                        </div>
                        <p v-if="errors.last_name">
                            <span v-for="error in errors.last_name">{{error}}<br></span>
                        </p>
                        <div v-if="!socialAuth" class="input-group form-group-no-border">
                        <span class="input-group-addon">
                            <i class="fa fa-envelope"></i>
                        </span>
                            <input type="email" class="form-control" placeholder="Email..." v-model="email" required>
                        </div>
                        <p v-if="errors.email">
                            <span v-for="error in errors.email">{{error}}<br></span>
                        </p>
                        <div class="input-group form-group-no-border">
                        <span class="input-group-addon">
                            <i class="fa fa-lock"></i>
                        </span>
                            <input type="password" class="form-control" placeholder="Password..." v-model="password"
                                   required>
                        </div>
                        <div class="input-group form-group-no-border">
                        <span class="input-group-addon">
                            <i class="fa fa-lock"></i>
                        </span>
                            <input type="password" class="form-control" placeholder="Confirm Password..."
                                   v-model="password_confirmation" required>
                        </div>
                        <p v-if="errors.password">
                            <span v-for="error in errors.password">{{error}}<br></span>
                        </p>
                        <div class="form-group-no-border">
                            <label style="margin-right: 60px">Gender</label>
                            <input type="radio" v-model="gender"
                                   value="Male"><span style="margin-right: 50px"> Male</span>
                            <input type="radio" v-model="gender"
                                   value="Female"> Female
                        </div>
                        <p v-if="errors.gender">
                            <span v-for="error in errors.gender">{{error}}<br></span>
                        </p>
                        <div class="input-group form-group-no-border">
                        <span class="input-group-addon">
                            <i class="fa fa-phone"></i>
                        </span>
                            <input type="number" class="form-control" placeholder="Phone..." v-model="phone"
                                   required>
                        </div>
                        <p v-if="errors.phone">
                            <span v-for="error in errors.phone">{{error}}<br></span>
                        </p>
                        <div class="input-group form-group-no-border">
                        <span class="input-group-addon">
                            <i class="fa fa-briefcase"></i>
                        </span>
                            <input type="text" class="form-control" placeholder="Occupation..." v-model="occupation">
                        </div>
                        <p v-if="errors.phone">
                            <span v-for="error in errors.phone">{{error}}<br></span>
                        </p>

                    </div>
                    <div class="mx-auto py-3">
                        <button type="submit" class="btn btn-neutral btn-round btn-lg" :disabled="loading">
                            <i v-if="loading" class="fa fa-spinner"></i>
                            <span v-else>Sign Up</span>
                        </button>
                    </div>
                </form>
            </div>
            <a class="white--text title-small" @click.prevent="change">{{ message }}</a>
        </div>
    </div>
</template>

<script>
    import {AUTH_REQUEST} from "../store/actions";

    export default {
        name: "app-auth",
        props: ['active', 'stylesection', 'login', 'socialAuth', 'passwordReset'],
        data() {
            return {
                firstName: '',
                lastName: '',
                email: '',
                password: '',
                password_confirmation: '',
                gender: '',
                phone: '',
                occupation: '',
                errors: {},
                passwordForget: false,
                passwordForgetRequest: false,
                loading: false
            }
        },
        created() {
            window.addEventListener('click', (e) => {
                if (e.target === document.querySelector('.loginSignup'))
                    this.close();
            });
        },
        computed: {
            message() {
                return this.login ? 'Don\'t have an account?' : 'Already have an account?';
            },
            loginButtonText() {
                if (this.passwordForgetRequest)
                    return 'Send Password Reset Link';
                else if (this.passwordReset)
                    return 'Reset';
                else return 'Login';
            }
        },
        methods: {
            close() {
                this.resetData();
                this.$emit('close');
            },
            change() {
                this.resetData();
                this.$emit('change');
            },
            onPasswordForget() {
                this.errors = {};
                this.passwordForget = false;
                this.passwordForgetRequest = true;
            },
            onSignUp() {
                this.loading = true;
                if (this.socialAuth) {
                    axios.put(`/api/register/${window.access_token}`,
                        {
                            first_name: this.firstName,
                            last_name: this.lastName,
                            password: this.password,
                            password_confirmation: this.password_confirmation,
                            gender: this.gender,
                            phone: this.phone,
                            occupation: this.occupation
                        })
                        .then(response => {
                            console.log(response);
                            this.$emit('message', response.data.message);
                            this.$store.dispatch(AUTH_REQUEST, {email: window.access_token, password: this.password})
                                .then(() => {
                                    this.resetData();
                                    window.access_token = '';
                                    window.verified = '';
                                    this.loading = false;
                                    this.$emit('close');
                                })
                        })
                        .catch(error => {
                            this.errors = error.response.data.errors;
                            this.loading = false;
                        });
                } else {
                    axios.post('/api/register',
                        {
                            first_name: this.firstName,
                            last_name: this.lastName,
                            email: this.email,
                            password: this.password,
                            password_confirmation: this.password_confirmation,
                            gender: this.gender,
                            phone: this.phone,
                            occupation: this.occupation
                        })
                        .then(response => {
                            this.resetData();
                            this.loading = false;
                            this.$emit('message', response.data.message);
                            this.$emit('close');
                        })
                        .catch(error => {
                            this.errors = error.response.data.errors;
                            this.loading = false;
                        });
                }

            },
            onLogin() {
                this.loading = true;
                if (this.passwordReset) {
                    axios.post('/api/password/reset',
                        {
                            token: window.reset_token,
                            email: this.email,
                            password: this.password,
                            password_confirmation: this.password_confirmation
                        })
                        .then(response => {
                            window.reset_token = '';
                            this.resetData();
                            this.loading = false;
                            this.$emit('message', response.data.message);
                            this.$emit('close');
                        })
                        .catch(error => {
                            this.errors = error.response.data.errors;
                            this.loading = false;
                        });
                }
                else if (this.passwordForgetRequest) {
                    axios.post('/api/password/email',{email: this.email})
                        .then(response => {
                            this.resetData();
                            this.loading = false;
                            this.$emit('message', response.data.message);
                            this.$emit('close');
                        })
                        .catch(error => {
                            this.errors = error.response.data.errors;
                            this.loading = false;
                        });
                }
                else {
                    this.$store.dispatch(AUTH_REQUEST, {email: this.email, password: this.password})
                        .then(() => {
                            this.resetData();
                            this.loading = false;
                            this.$emit('close');
                        })
                        .catch(error => {
                            this.passwordForget = true;
                            this.loading = false;
                        })
                }
            },
            resetData() {
                this.firstName = '';
                this.lastName = '';
                this.email = '';
                this.password = '';
                this.password_confirmation = '';
                this.gender = '';
                this.phone = '';
                this.occupation = '';
                this.passwordForget = false;
                this.passwordForgetRequest = false;
                this.errors = {};
            }
        }
    }
</script>

<style scoped>

    .input-group {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        width: 100%
    }

    .input-group .form-control {
        position: relative;
        z-index: 2;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        width: 1%;
        margin-bottom: 0
    }

    .input-group .form-control:active,
    .input-group .form-control:focus,
    .input-group .form-control:hover {
        z-index: 3
    }

    .input-group .form-control,
    .input-group-addon {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center
    }

    .input-group-addon {
        white-space: nowrap;
        vertical-align: middle
    }

    .input-group-addon {
        padding: .5rem .75rem;
        margin-bottom: 0;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.25;
        color: #495057;
        text-align: center;
        background-color: #e9ecef;
        border: 1px solid rgba(0, 0, 0, .15);
        border-radius: .25rem
    }

    .input-group-sm > .input-group-addon,
    .input-group-sm > .input-group-btn > .input-group-addon.btn {
        padding: .25rem .5rem;
        font-size: .875rem;
        border-radius: .2rem
    }

    .input-group-lg > .input-group-addon,
    .input-group-lg > .input-group-btn > .input-group-addon.btn {
        padding: .5rem 1rem;
        font-size: 1.25rem;
        border-radius: .3rem
    }

    .input-group .form-control:not(:last-child),
    .input-group-addon:not(:last-child),
    .input-group-btn:not(:first-child) > .btn-group:not(:last-child) > .btn,
    .input-group-btn:not(:first-child) > .btn:not(:last-child):not(.dropdown-toggle),
    .input-group-btn:not(:last-child) > .btn,
    .input-group-btn:not(:last-child) > .btn-group > .btn {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0
    }

    .input-group-addon:not(:last-child) {
        border-right: 0
    }

    .input-group .form-control:not(:first-child),
    .input-group-addon:not(:first-child),
    .input-group-btn:not(:first-child) > .btn,
    .input-group-btn:not(:first-child) > .btn-group > .btn,
    .input-group-btn:not(:last-child) > .btn-group:not(:first-child) > .btn,
    .input-group-btn:not(:last-child) > .btn:not(:first-child) {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0
    }

    .form-control + .input-group-addon:not(:first-child) {
        border-left: 0
    }

    .input-group .form-control {
        position: relative;
        z-index: 2;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        width: 1%;
        margin-bottom: 0
    }

    .input-group .form-control:active,
    .input-group .form-control:focus,
    .input-group .form-control:hover {
        z-index: 3
    }

    .input-group .form-control,
    .input-group-addon {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center
    }

    .card {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, .125);
        border-radius: .25rem
    }

    .card-body {
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        padding: 1rem
    }

    .loginSignup {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1000; /* Sit on top */
        padding-top: 75px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0, 0, 0); /* Fallback color */
        background-color: rgba(10, 10, 10, 0.86); /* Black w/ opacity */
        text-align: center;
    }

    /* Modal Content */
    .loginSignup-content {
        position: relative;
        background-color: #f96332;
        margin: 0 auto;
        padding: 0;
        padding-bottom: 8px;
        border: 1px solid #888;
        max-width: 350px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        -webkit-animation-name: animatetop;
        -webkit-animation-duration: 0.4s;
        animation-name: animatetop;
        animation-duration: 0.8s
    }

    /* Add Animation */
    @-webkit-keyframes animatetop {
        from {
            top: -300px;
            opacity: 0
        }
        to {
            top: 0;
            opacity: 1
        }
    }

    @keyframes animatetop {
        from {
            top: -300px;
            opacity: 0
        }
        to {
            top: 0;
            opacity: 1
        }
    }

    .card-signup .social-line .btn.btn-icon, .card-signup .social-line .btn.btn-icon {
        margin-left: 5px;
        margin-right: 5px;
        box-shadow: 0px 5px 50px 0px rgba(0, 0, 0, 0.2);
    }

    .btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        border: 1px solid transparent;
        padding: .5rem .75rem;
        font-size: 1rem;
        line-height: 1.25;
        border-radius: .25rem;
        transition: all .15s ease-in-out;
    }

    .btn:hover,
    .btn:focus {
        opacity: 1;
        filter: alpha(opacity=100);
        outline: 0 !important;
    }

    .btn:active {
        -webkit-box-shadow: none;
        box-shadow: none;
        outline: 0 !important;
    }

    .btn.btn-icon {
        height: 2.375rem;
        min-width: 2.375rem;
        width: 2.375rem;
        padding: 0.5em .75em;
        font-size: 0.9375rem;
        overflow: hidden;
        position: relative;
        line-height: normal;
    }

    .btn-neutral {
        background-color: #FFFFFF;
        color: #f96332;
    }

    .btn-neutral:hover,
    .btn-neutral:focus,
    .btn-neutral:active,
    .btn-neutral:active:focus,
    .btn-neutral:active:hover {
        background-color: #FFFFFF;
        color: #FFFFFF;
        box-shadow: none;
    }

    .btn-neutral:hover {
        box-shadow: 0 3px 8px 0 rgba(0, 0, 0, 0.17);
    }

    .btn-neutral:active,
    .btn-neutral:active:focus,
    .btn-neutral:active:hover {
        background-color: #FFFFFF;
        color: #fa7a50;
        box-shadow: none;
    }

    .btn-neutral:hover,
    .btn-neutral:focus {
        color: #fa7a50;
    }

    .btn-round {
        border-width: 1px;
        border-radius: 30px !important;
        padding: 11px 23px;
    }

    .form-control::-moz-placeholder {
        color: #DDDDDD;
        opacity: 1;
        filter: alpha(opacity=100);
    }

    .form-control:-moz-placeholder {
        color: #DDDDDD;
        opacity: 1;
        filter: alpha(opacity=100);
    }

    .form-control::-webkit-input-placeholder {
        color: #DDDDDD;
        opacity: 1;
        filter: alpha(opacity=100);
    }

    .form-control:-ms-input-placeholder {
        color: #DDDDDD;
        opacity: 1;
        filter: alpha(opacity=100);
    }

    .form-control {
        background-color: transparent;
        border: 1px solid #E3E3E3;
        border-radius: 30px;
        color: #2c2c2c;
        line-height: normal;
        font-size: 0.8571em;
        -webkit-transition: color 0.3s ease-in-out, border-color 0.3s ease-in-out, background-color 0.3s ease-in-out;
        -moz-transition: color 0.3s ease-in-out, border-color 0.3s ease-in-out, background-color 0.3s ease-in-out;
        -o-transition: color 0.3s ease-in-out, border-color 0.3s ease-in-out, background-color 0.3s ease-in-out;
        -ms-transition: color 0.3s ease-in-out, border-color 0.3s ease-in-out, background-color 0.3s ease-in-out;
        transition: color 0.3s ease-in-out, border-color 0.3s ease-in-out, background-color 0.3s ease-in-out;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    .form-control:focus {
        border: 1px solid #f96332;
        -webkit-box-shadow: none;
        box-shadow: none;
        outline: 0 !important;
        color: #2c2c2c;
    }

    .form-control:focus + .input-group-addon,
    .form-control:focus ~ .input-group-addon {
        border: 1px solid #f96332;
        border-left: none;
        background-color: transparent;
    }

    .form-control + .input-group-addon {
        background-color: #FFFFFF;
    }

    .form-group.form-group-no-border.input-lg .input-group-addon,
    .input-group.form-group-no-border.input-lg .input-group-addon {
        padding: 15px 0 15px 19px;
    }

    .form-group.form-group-no-border.input-lg .form-control,
    .input-group.form-group-no-border.input-lg .form-control {
        padding: 15px 19px;
    }

    .form-group.form-group-no-border.input-lg .form-control + .input-group-addon,
    .input-group.form-group-no-border.input-lg .form-control + .input-group-addon {
        padding: 15px 19px 15px 0;
    }

    .form-group.input-lg .form-control,
    .input-group.input-lg .form-control {
        padding: 14px 18px;
    }

    .form-group.input-lg .form-control + .input-group-addon,
    .input-group.input-lg .form-control + .input-group-addon {
        padding: 14px 18px 14px 0;
    }

    .form-group.input-lg .input-group-addon,
    .input-group.input-lg .input-group-addon {
        padding: 14px 18px 15px 18px;
    }

    .form-group.input-lg .input-group-addon + .form-control,
    .input-group.input-lg .input-group-addon + .form-control {
        padding: 15px 18px 15px 16px;
    }

    .form-group.form-group-no-border .form-control,
    .input-group.form-group-no-border .form-control {
        padding: 11px 19px;
    }

    .form-group.form-group-no-border .form-control + .input-group-addon,
    .input-group.form-group-no-border .form-control + .input-group-addon {
        padding: 11px 19px 11px 0;
    }

    .form-group.form-group-no-border .input-group-addon,
    .input-group.form-group-no-border .input-group-addon {
        padding: 11px 0 11px 19px;
    }

    .form-group .form-control,
    .input-group .form-control {
        padding: 10px 18px 10px 18px;
    }

    .form-group .form-control + .input-group-addon,
    .input-group .form-control + .input-group-addon {
        padding: 10px 18px 10px 0;
    }

    .form-group .input-group-addon,
    .input-group .input-group-addon {
        padding: 10px 0 10px 18px;
    }

    .form-group .input-group-addon + .form-control,
    .form-group .input-group-addon ~ .form-control,
    .input-group .input-group-addon + .form-control,
    .input-group .input-group-addon ~ .form-control {
        padding: 10px 19px 11px 16px;
    }

    .form-group.form-group-no-border .form-control,
    .form-group.form-group-no-border .form-control + .input-group-addon,
    .input-group.form-group-no-border .form-control,
    .input-group.form-group-no-border .form-control + .input-group-addon {
        background-color: rgba(222, 222, 222, 0.3);
        border: medium none;
    }

    .form-group.form-group-no-border .form-control:focus,
    .form-group.form-group-no-border .form-control:active,
    .form-group.form-group-no-border .form-control:active,
    .form-group.form-group-no-border .form-control + .input-group-addon:focus,
    .form-group.form-group-no-border .form-control + .input-group-addon:active,
    .form-group.form-group-no-border .form-control + .input-group-addon:active,
    .input-group.form-group-no-border .form-control:focus,
    .input-group.form-group-no-border .form-control:active,
    .input-group.form-group-no-border .form-control:active,
    .input-group.form-group-no-border .form-control + .input-group-addon:focus,
    .input-group.form-group-no-border .form-control + .input-group-addon:active,
    .input-group.form-group-no-border .form-control + .input-group-addon:active {
        border: medium none;
        background-color: rgba(222, 222, 222, 0.5);
    }

    .form-group.form-group-no-border .form-control:focus + .input-group-addon,
    .input-group.form-group-no-border .form-control:focus + .input-group-addon {
        background-color: rgba(222, 222, 222, 0.5);
    }

    .form-group.form-group-no-border .input-group-addon,
    .input-group.form-group-no-border .input-group-addon {
        background-color: rgba(222, 222, 222, 0.3);
        border: none;
    }

    .input-group-addon {
        background-color: #FFFFFF;
        border: 1px solid #E3E3E3;
        border-radius: 30px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        color: #555555;
        padding: -0.5rem -0 -0.5rem -0.3rem;
        -webkit-transition: color 0.3s ease-in-out, border-color 0.3s ease-in-out, background-color 0.3s ease-in-out;
        -moz-transition: color 0.3s ease-in-out, border-color 0.3s ease-in-out, background-color 0.3s ease-in-out;
        -o-transition: color 0.3s ease-in-out, border-color 0.3s ease-in-out, background-color 0.3s ease-in-out;
        -ms-transition: color 0.3s ease-in-out, border-color 0.3s ease-in-out, background-color 0.3s ease-in-out;
        transition: color 0.3s ease-in-out, border-color 0.3s ease-in-out, background-color 0.3s ease-in-out;
    }

    .input-group-addon + .form-control,
    .input-group-addon ~ .form-control {
        padding: -0.5rem 0.7rem;
        padding-left: 18px;
    }

    .input-group-addon i {
        width: 17px;
    }

    .input-group-focus .input-group-addon {
        background-color: #FFFFFF;
        border-color: #f96332;
    }

    .input-group-focus.form-group-no-border .input-group-addon {
        background-color: rgba(222, 222, 222, 0.5);
    }

    .input-group {
        margin-bottom: 10px;
    }

    button,
    input {
        font-family: "Montserrat", "Helvetica Neue", Arial, sans-serif;
    }

    .title {
        font-weight: 700;
    }

    .title-small {
        color: white;
        cursor: pointer;
    }

    .title-small a,
    .title-small a:hover,
    .title-small a:active,
    .title-small a:not([href]):not([tabindex]) {
        color: #FFFFFF;
        text-decoration: none;
    }

    .btn-facebook {
        color: #3b5998;
    }

    .btn-facebook:hover,
    .btn-facebook:focus,
    .btn-facebook:active {
        color: #344e86;
    }

    .btn-google {
        color: #dd4b39;
    }

    .btn-google:hover,
    .btn-google:focus,
    .btn-google:active {
        color: #d73925;
    }

    .card {
        border: 0;
        border-radius: 0.1875rem;
        display: inline-block;
        position: relative;
        width: 100%;
        margin-bottom: 20px;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    .card .card-body {
        min-height: 190px;
    }

    .card[data-background-color="orange"] {
        background-color: #f96332;
    }

    .card[data-background-color="red"] {
        background-color: #FF3636;
    }

    .card[data-background-color="yellow"] {
        background-color: #FFB236;
    }

    .card[data-background-color="blue"] {
        background-color: #2CA8FF;
    }

    .card[data-background-color="green"] {
        background-color: #18ce0f;
    }

    .card-signup {
        max-width: 350px;
        margin: 0 auto;
    }

    .card-signup .header {
        margin-left: 20px;
        margin-right: 20px;
        padding: 20px 0;
        font-size: medium;
    }

    .card-signup .card-body {
        padding-top: 0px;
        padding-bottom: 0px;
        min-height: auto;
    }

    .card-signup .social-line {
        margin-top: 20px;
        text-align: center;
    }

    .card-signup .social-line .btn.btn-icon {
        margin-left: 5px;
        margin-right: 5px;
        box-shadow: 0px 5px 50px 0px rgba(0, 0, 0, 0.2);
    }

    .card-signup .footer {
        margin-bottom: 10px;
        margin-top: 24px;
        text-align: center;
    }

    [data-background-color="orange"] {
        background-color: #e95e38;
    }

    [data-background-color="black"] {
        background-color: #2c2c2c;
    }

    [data-background-color] {
        color: #FFFFFF;
    }

    [data-background-color] .title, title-small {
        color: #FFFFFF;
    }

    [data-background-color] .form-control::-moz-placeholder {
        color: #ebebeb;
        opacity: 1;
        filter: alpha(opacity=100);
    }

    [data-background-color] .form-control:-moz-placeholder {
        color: #ebebeb;
        opacity: 1;
        filter: alpha(opacity=100);
    }

    [data-background-color] .form-control::-webkit-input-placeholder {
        color: #ebebeb;
        opacity: 1;
        filter: alpha(opacity=100);
    }

    [data-background-color] .form-control:-ms-input-placeholder {
        color: #ebebeb;
        opacity: 1;
        filter: alpha(opacity=100);
    }

    [data-background-color] .form-control {
        border-color: rgba(255, 255, 255, 0.5);
        color: #FFFFFF;
    }

    [data-background-color] .form-control:focus {
        border-color: #FFFFFF;
        background-color: transparent;
        color: #FFFFFF;
    }

    [data-background-color] .has-danger .form-control {
        background-color: transparent;
    }

    [data-background-color] .input-group-addon {
        background-color: transparent;
        border-color: rgba(255, 255, 255, 0.5);
        color: #FFFFFF;
    }

    [data-background-color] .input-group-focus .input-group-addon {
        background-color: transparent;
        border-color: #FFFFFF;
        color: #FFFFFF;
    }

    [data-background-color] .form-group.form-group-no-border .form-control,
    [data-background-color] .input-group.form-group-no-border .form-control {
        background-color: rgba(255, 255, 255, 0.1);
        color: #FFFFFF;
    }

    [data-background-color] .form-group.form-group-no-border .form-control:focus,
    [data-background-color] .form-group.form-group-no-border .form-control:active,
    [data-background-color] .form-group.form-group-no-border .form-control:active,
    [data-background-color] .input-group.form-group-no-border .form-control:focus,
    [data-background-color] .input-group.form-group-no-border .form-control:active,
    [data-background-color] .input-group.form-group-no-border .form-control:active {
        background-color: rgba(255, 255, 255, 0.2);
        color: #FFFFFF;
    }

    [data-background-color] .form-group.form-group-no-border .form-control + .input-group-addon,
    [data-background-color] .input-group.form-group-no-border .form-control + .input-group-addon {
        background-color: rgba(255, 255, 255, 0.1);
    }

    [data-background-color] .form-group.form-group-no-border .form-control + .input-group-addon:focus,
    [data-background-color] .form-group.form-group-no-border .form-control + .input-group-addon:active,
    [data-background-color] .form-group.form-group-no-border .form-control + .input-group-addon:active,
    [data-background-color] .input-group.form-group-no-border .form-control + .input-group-addon:focus,
    [data-background-color] .input-group.form-group-no-border .form-control + .input-group-addon:active,
    [data-background-color] .input-group.form-group-no-border .form-control + .input-group-addon:active {
        background-color: rgba(255, 255, 255, 0.2);
        color: #FFFFFF;
    }

    [data-background-color] .form-group.form-group-no-border .form-control:focus + .input-group-addon,
    [data-background-color] .input-group.form-group-no-border .form-control:focus + .input-group-addon {
        background-color: rgba(255, 255, 255, 0.2);
        color: #FFFFFF;
    }

    [data-background-color] .form-group.form-group-no-border .input-group-addon,
    [data-background-color] .input-group.form-group-no-border .input-group-addon {
        background-color: rgba(255, 255, 255, 0.1);
        border: none;
        color: #FFFFFF;
    }

    [data-background-color] .form-group.form-group-no-border.input-group-focus .input-group-addon,
    [data-background-color] .input-group.form-group-no-border.input-group-focus .input-group-addon {
        background-color: rgba(255, 255, 255, 0.2);
        color: #FFFFFF;
    }

    [data-background-color] .input-group-addon,
    [data-background-color] .form-group.form-group-no-border .input-group-addon,
    [data-background-color] .input-group.form-group-no-border .input-group-addon {
        color: rgba(255, 255, 255, 0.8);
    }

</style>
