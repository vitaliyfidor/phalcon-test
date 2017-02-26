{% extends "templates/base.volt" %}
{% block content %}
<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        {{ content() }}
        {{ form('signup/login', 'method': 'post', "class": "form-horizontal") }}
            <div class="form-group">
                {{ form.label('username', ['class': 'col-sm-4 control-label']) }}
                <div class="col-sm-8">
                    {{ form.render('username', ['class': 'form-control', 'placeholder': 'username']) }}
                </div>
            </div>
            <div class="form-group">
                {{ form.label('password', ['class': 'col-sm-4 control-label']) }}
                <div class="col-sm-8">
                    {{ form.render('password', ['class': 'form-control']) }}
                </div>
            </div>
            <div class="form-group">
                {{ submit_button('Login', "class": "btn btn-primary btn-lg", "role": "button") }}
            </div>
        {{ end_form() }}
    </div>
    <div class="col-md-4"></div>
</div>
{% endblock %}