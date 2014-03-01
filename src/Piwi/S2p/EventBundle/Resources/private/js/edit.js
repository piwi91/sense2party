$(function() {
    $.widget('sense2party.eventEdit', {
        // Default options
        options: {
        },

        // Private variables

        /**
         * The constructor
         *
         * @private
         */
        _create: function() {
            this._on(this.document, {
                'click.delete': function(event) {
                    event.preventDefault();
                    var location = $(event.currentTarget).attr('href');
                    bootbox.confirm("Are you sure?", function(result) {
                        if (result !== false) {
                            window.location.replace(location);
                        }
                    });
                }
            });
        },

        /**
         * Is called with a hash of all options that are changing always refresh when changing options
         *
         * @private
         */
        _setOptions: function() {
            // _super and _superApply handle keeping the right this-context
            this._superApply(arguments);
        },

        /**
         * Is called for each individual option that is changing
         *
         * @param key
         * @param value
         * @private
         */
        _setOption: function(key, value) {
            this._super(key, value);
        },

        /**
         * Called when created, and later when changing options
         *
         * @private
         */
        _refresh: function() {

        },

        /**
         * Events bound via _on are removed automatically
         *
         * @private
         */
        _destroy: function() {
            // Revert other modifications here
            this.element.removeClass('hotflo-clock');

            // Call the base destroy function
            $.Widget.prototype.destroy.call(this);
        }
    });
}(jQuery));