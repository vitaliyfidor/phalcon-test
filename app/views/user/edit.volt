{% extends "templates/base.volt" %}
{% block content %}
<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
    {{ content() }}
        {{ form('user/save', 'method': 'post', 'class': 'form-horizontal', 'id': 'updateForm', 'onbeforesubmit': 'return false') }}
        {{ form.render('id') }}
            <div class="form-group">
                {{ form.label('username', ['class': 'col-sm-4 control-label']) }}
                <div class="col-sm-8">
                {{ form.render('username', ['class': 'form-control', 'placeholder': 'username']) }}
                </div>
            </div>
            <div class="form-group">
                {{ form.label('email', ['class': 'col-sm-4 control-label']) }}
                <div class="col-sm-8">
                    {{ form.render('email', ['class': 'col-sm-4 form-control', 'placeholder': 'your@email.com']) }}
                </div>
            </div>
            <div class="form-group">
                {{ form.label('password', ['class': 'col-sm-4 control-label']) }}
                <div class="col-sm-8">
                    {{ form.render('password', ['class': 'form-control']) }}
                </div>
            </div>
            <div class="form-group">
                {{ form.label('role', ['class': 'col-sm-4 control-label']) }}
                <div class="col-sm-8">
                    {{ form.render('role', ['class': 'form-control']) }}
                </div>
            </div>
            <div class="form-group">
                {{ form.label('groupsIds[]', ['class': 'col-sm-4 control-label']) }}
                <div class="col-sm-8">
                {{ form.render('groupsIds[]', ['class': 'form-control']) }}
                </div>
            </div>
            <div class="form-group">
                {{ submit_button('Update', "class": "btn btn-primary btn-lg", "role": "button") }}
            </div>
        {{ end_form() }}
    </div>
    <div class="col-md-4"></div>
</div>
{% endblock %}