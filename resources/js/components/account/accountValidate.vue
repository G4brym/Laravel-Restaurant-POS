<template>
    <div class="form-group" :class="{'has-error': !valid}">
        <label :for="inputId">{{ fieldName.charAt(0).toUpperCase() + fieldName.substr(1) }}</label>
        <input
            type="text" class="form-control" v-model.trim.lazy="mutableValue"
            :name="fieldName" :id="inputId"
            :placeholder="fieldName.charAt(0).toUpperCase() + fieldName.substr(1)"
            @change="verify()"/>
        <span v-show="!valid" class="help-block">
            <span v-for="line in errors">
                <template v-if='line === "\n"'>
                    <br/>
                </template>
                <template v-else>
                    {{ line }}
                </template>
            </span>
        </span>
    </div>
</template>

<script>
    export default {
        props: ["inputId", "value", "fieldName", "length", "regex", "regexError"],
        data: function() {
            return {
                mutableValue: this.value,
                valid: true,
                errors: []
            }
        },
        methods: {
            verify: function () {
                let field = this.mutableValue;
                let errors = [];
                if (field.length < this.length) {
                    errors.push(this.fieldName.charAt(0).toUpperCase() + this.fieldName.substr(1) +
                                " must have at least " + this.length + " characters");
                }
                if (!field.match(this.regex)) {
                    if (errors.length !== 0) {
                        errors.push("\n");
                    }
                    errors.push(this.regexError);
                }
                if (errors.length === 0) {
                    this.valid = true;
                } else {
                    this.errors = errors;
                    this.valid = false;
                }
            },
        },
        watch: {
            mutableValue: function(newValue) {
                this.$emit('input', newValue);
            },
            valid: function(newValid) {
                this.$emit('update-valid', newValid);
            }
        }
    }
</script>
