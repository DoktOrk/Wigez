<% extends("layouts/empty") %>

<% part("content") %>
<div id="main-wrapper">
    <!-- Title -->
    <h1 class="section-title">
        <span>Login</span>
    </h1><!-- End Title -->

    <div class="section section-custom login-card-section">
        <!-- section content start-->
        <div class="pmd-card card-default pmd-z-depth">
            <div class="login-card">
                <form>
                    <div class="pmd-card-body">
                        <% if (false) %>
                        <div class="alert alert-success" role="alert"> Oh snap! Change a few things up and try
                            submitting again.
                        </div>
                        <% endif %>
                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                            <label for="inputError1" class="control-label pmd-input-group-label">Username</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i
                                            class="material-icons md-dark pmd-sm">perm_identity</i></div>
                                <input class="form-control" id="exampleInputAmount" type="text"><span
                                        class="pmd-textfield-focused"></span>
                            </div>
                        </div>

                        <div class="form-group pmd-textfield pmd-textfield-floating-label">
                            <label for="inputError1" class="control-label pmd-input-group-label">Password</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">lock_outline</i>
                                </div>
                                <input class="form-control" id="exampleInputAmount" type="text"><span
                                        class="pmd-textfield-focused"></span>
                            </div>
                        </div>
                    </div>
                    <div class="pmd-card-footer card-footer-no-border card-footer-p16 text-center">
                        <div class="form-group clearfix">
                            <div class="checkbox pull-left">
                                <label class="pmd-checkbox checkbox-pmd-ripple-effect">
                                    <input checked="" value="" type="checkbox">
                                    <span class="pmd-checkbox-label">&nbsp;</span><span class="pmd-checkbox"> Remember me</span>
                                </label>
                            </div>
                            <span class="pull-right forgot-password">
                                    <a href="javascript:void(0);">Forgot password?</a>
                                </span>
                        </div>
                        <a href="index.html" type="button" class="btn pmd-ripple-effect btn-primary btn-block">Login</a>
                    </div>

                </form>
            </div>
        </div>
        <!-- section content end -->
    </div>
</div>
<% endpart %>