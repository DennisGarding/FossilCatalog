class Translation {

    translations = {}
    defaultTranslations = {};

    constructor(translations, defaultTranslations) {
        this.translations = translations;
        this.defaultTranslations = defaultTranslations;
    }

    trans(key) {
        const keys = key.split('.');

        let translation = this._getTranslation(keys, this.translations);
        if (translation !== null) {
            return translation;
        }

        translation = this._getTranslation(keys, this.defaultTranslations);
        if (translation !== null) {
            return translation;
        }

        return 'No translation found for key: ' + key;
    }

    _getTranslation(keys, translations) {
        let current = translations;

        keys.forEach((key) => {
            if (current[key] !== undefined) {
                current = current[key];
            }
        });

        if (typeof current === 'string') {
            return current;
        }

        return null;
    }
}