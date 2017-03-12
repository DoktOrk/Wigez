<% extends("layouts/empty") %>

<% part("content") %>

<div class="logincard">
    <div class="pmd-card card-default pmd-z-depth">
        <div class="login-card">
            <form lpformnum="1">
                <div class="pmd-card-title card-header-border text-center">
                    <h3>Sign In</h3>
                </div>

                <div class="pmd-card-body">
                    <div class="alert alert-success" role="alert"> Oh snap! Change a few things up and try submitting
                        again.
                    </div>
                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="inputError1" class="control-label pmd-input-group-label">Username</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">perm_identity</i>
                            </div>
                            <input type="text" class="form-control" id="exampleInputAmount"><span
                                    class="pmd-textfield-focused"></span>
                        </div>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="inputError1" class="control-label pmd-input-group-label">Password</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">lock_outline</i>
                            </div>
                            <input type="text" class="form-control" id="exampleInputAmount"><span
                                    class="pmd-textfield-focused"></span>
                        </div>
                    </div>
                </div>
                <div class="pmd-card-footer card-footer-no-border card-footer-p16 text-center">
                    <div class="form-group clearfix">
                        <div class="checkbox pull-left">
                            <label class="pmd-checkbox checkbox-pmd-ripple-effect">
                                <input type="checkbox" checked="" value=""><span
                                        class="pmd-checkbox-label">&nbsp;</span>
                                <span class="pmd-checkbox"> Remember me</span>
                            </label>
                        </div>
                        <span class="pull-right forgot-password">
                            <a href="javascript:void(0);">Forgot password?</a>
                        </span>
                    </div>
                    <a href="index.html" type="button" class="btn pmd-ripple-effect btn-primary btn-block">Login</a>

                    <p class="redirection-link">Don't have an account? <a href="javascript:void(0);"
                                                                          class="login-register">Sign Up</a>. </p>

                </div>

            </form>
        </div>

        <div class="register-card">
            <div class="pmd-card-title card-header-border text-center">
                <div class="loginlogo">
                    <a href="javascript:void(0);"><img src="themes/images/logo-icon.png" alt="Logo"></a>
                </div>
                <h3>Sign Up <span>with <strong>PROPELLER</strong></span></h3>
            </div>
            <form lpformnum="2">
                <div class="pmd-card-body">

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="inputError1" class="control-label pmd-input-group-label">Username</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">perm_identity</i>
                            </div>
                            <input type="text" class="form-control" id="exampleInputAmount"><span
                                    class="pmd-textfield-focused"></span>
                        </div>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="inputError1" class="control-label pmd-input-group-label">Email address</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">email</i></div>
                            <input type="text" class="form-control" id="exampleInputAmount"><span
                                    class="pmd-textfield-focused"></span>
                        </div>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="inputError1" class="control-label pmd-input-group-label">Password</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">lock_outline</i>
                            </div>
                            <input type="text" class="form-control" id="exampleInputAmount"><span
                                    class="pmd-textfield-focused"></span>
                        </div>
                    </div>
                </div>

                <div class="pmd-card-footer card-footer-no-border card-footer-p16 text-center">
                    <a href="index.html" type="button" class="btn pmd-ripple-effect btn-primary btn-block">Sign Up</a>
                    <p class="redirection-link">Already have an account? <a href="javascript:void(0);"
                                                                            class="register-login">Sign In</a>. </p>
                </div>
            </form>
        </div>

        <div class="forgot-password-card">
            <form lpformnum="3">
                <div class="pmd-card-title card-header-border text-center">
                    <div class="loginlogo">
                        <a href="javascript:void(0);"><img src="themes/images/logo-icon.png" alt="Logo"></a>
                    </div>
                    <h3>Forgot password?<br><span>Submit your email address and we'll send you a link to reset your password.</span>
                    </h3>
                </div>
                <div class="pmd-card-body">
                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="inputError1" class="control-label pmd-input-group-label">Email address</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">email</i></div>
                            <input type="text" class="form-control" id="exampleInputAmount"><span
                                    class="pmd-textfield-focused"></span>
                        </div>
                    </div>
                </div>
                <div class="pmd-card-footer card-footer-no-border card-footer-p16 text-center">
                    <a href="index.html" type="button" class="btn pmd-ripple-effect btn-primary btn-block">Submit</a>
                    <p class="redirection-link">Already registered? <a href="javascript:void(0);"
                                                                       class="register-login">Sign In</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<% endpart %>