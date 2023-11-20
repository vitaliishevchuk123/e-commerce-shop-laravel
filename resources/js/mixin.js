const myMixin = {
    methods: {
        /**
         * Translate the given key.
         */
        __(key, replace = {}) {
            let translation = this.$page.props.language[key]
                ? this.$page.props.language[key]
                : key

            Object.keys(replace).forEach(function (key) {
                translation = translation.replace(':' + key, replace[key])
            });

            return translation
        },
    },
};

export default myMixin;
