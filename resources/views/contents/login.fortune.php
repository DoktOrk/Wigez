<% extends("layouts/empty") %>

<% part("content") %>

<div class="logincard">
    <div class="pmd-card card-default pmd-z-depth">
        <div class="login-card">
            <form method="post" action="{{! route('login-post') !}}">
                {{! csrfInput() !}}
                <div class="pmd-card-title card-header-border text-center">
                    <h3>Sign In</h3>
                </div>

                <div class="pmd-card-body">
                    <div class="alert alert-success" role="alert"> Oh snap! Change a few things up and try submitting
                        again.
                    </div>
                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="login-username" class="control-label pmd-input-group-label">Username</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">perm_identity</i>
                            </div>
                            <input type="text" class="form-control" name="username" id="login-username"><span
                                    class="pmd-textfield-focused"></span>
                        </div>
                    </div>

                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="login-password" class="control-label pmd-input-group-label">Password</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">lock_outline</i>
                            </div>
                            <input type="password" class="form-control" name="password" id="login-password"><span
                                    class="pmd-textfield-focused"></span>
                        </div>
                    </div>
                </div>
                <div class="pmd-card-footer card-footer-no-border card-footer-p16 text-center">
                    <button type="submit" class="btn pmd-ripple-effect btn-primary btn-block">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

<% endpart %>