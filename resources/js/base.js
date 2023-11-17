const myMixin = {
    methods: {
        /**
         * Translate the given key.
         */
        __(key, replace = {}) {
            let translation = this.$page.language[key]
                ? this.$page.language[key]
                : key

            Object.keys(replace).forEach(function (key) {
                translation = translation.replace(':' + key, replace[key])
            });

            return translation
        },
    },
};

export default myMixin;
