<?php
class AE_radio {

    /**
     * Field Constructor.
     *
     * @param array $field
     * - id
     * - name
     * - placeholder
     * - readonly
     * - class
     * - title
     * @param $value
     * @param $parent
     * @since AEFramework 1.0.0
     */
    function __construct( $field = array(), $value ='', $parent ) {

        //parent::__construct( $parent->sections, $parent->args );
        $this->parent = $parent;
        $this->field = $field;
        $this->value = $value;

    }

    /**
     * Field Render Function.
     *
     * Takes the vars and outputs the HTML for the field in the settings
     *
     * @since AEFramework 1.0.0
     */
    function render() {
        $data = $this->field['data'];
        echo '<div id="'. $this->field['id'] .'">';
        foreach($data as $key => $value) {
            ?>
            <div class="form-radio-item">
                <label for="<?php echo $key; ?>">
                    <input type="radio" class="regular-check" id="<?php echo $key; ?>"
                           name="<?php echo $this->field['name'] ?>"
                           value="<?php echo $key; ?>" <?php checked($this->value, $key, true); ?>>
                    <?php echo $value; ?>
                </label>
            </div>
            <?php
        }
    }//render
}
