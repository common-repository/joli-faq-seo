<?php

/**
 * @package jolifaq
 */
namespace WPJoli\JoliFAQ\Controllers\Callbacks;

class SettingsCallbacks {
    public function sanitizeColor( $input ) {
        $value = strtolower( (string) $input );
        if ( preg_match( '/^#[0-9a-f]{6}$/', $value ) ) {
            return $value;
        } else {
            if ( preg_match( '/^rgb\\((\\d{1,3}),\\s*(\\d{1,3}),\\s*(\\d{1,3})\\)$/', $value ) ) {
                return $value;
            } else {
                if ( preg_match( '/^rgba\\((\\d{1,3}),\\s*(\\d{1,3}),\\s*(\\d{1,3}),\\s*(\\d*(?:\\.\\d+)?)\\)$/', $value ) ) {
                    return $value;
                }
            }
        }
        return null;
    }

    public function sanitizeCheckbox( $input ) {
        return filter_var( $input, FILTER_SANITIZE_NUMBER_INT );
    }

    public function sanitizeNumber( $input ) {
        return filter_var( $input, FILTER_SANITIZE_NUMBER_INT );
    }

    public function sanitizeFloat( $input ) {
        return filter_var( $input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
    }

    public function sanitizeTextarea( $input ) {
        return sanitize_textarea_field( $input );
    }

    public function sanitizeText( $input ) {
        return sanitize_text_field( $input );
    }

    public function sanitizeSelect( $input ) {
        return sanitize_text_field( $input );
    }

    public function sanitizeUnit( $input ) {
        $value = $input;
        if ( substr_count( $value, '|' ) > 1 ) {
            return null;
        }
        $value = str_replace( ',', '.', $value );
        return $value;
    }

    private function doTemplate( $template, $data = [] ) {
        return str_replace( array_map( 'jfaq_mustache_key', array_keys( $data ) ), array_values( $data ), $template );
    }

    private function generateClassData( $classes = null, $data = null ) {
        $output = '';
        if ( !is_array( $data ) ) {
            return $classes;
        }
        if ( $data ) {
            foreach ( $data as $key => $value ) {
                $output .= sprintf( ' data-%s="%s"', $key, $value );
            }
            $output = rtrim( $output, '"' );
        }
        return $classes . '"' . $output;
    }

    /**
     * Echoes out the actual input field in the settings page
     */
    public function inputField( $args ) {
        $option = explode( '.', $args['id'] );
        $classes_data = $this->generateClassData( isset_or_null( $args['classes'], true ), isset_or_null( $args['data'] ) );
        $data = [
            'classes'     => $classes_data,
            'name'        => $args['name'],
            'placeholder' => isset_or_null( $args['placeholder'], true ),
            'option'      => $option,
            'value'       => jfaq_get_option( $option[1], $option[0] ),
        ];
        //echoes the corresponding field type
        echo $this->displayInput( $args, $data );
        //Adds a description if any
        if ( isset( $args['desc'] ) && $args['desc'] ) {
            if ( is_array( $args['desc'] ) ) {
                foreach ( $args['desc'] as $line ) {
                    echo sprintf( '<p class="description">%s</p>', $line );
                }
            } else {
                echo sprintf( '<p class="description">%s</p>', $args['desc'] );
            }
        }
        //Adds an image if any
        if ( isset( $args['img'] ) && $args['img'] ) {
            $img_path = JFAQ()->path( 'assets/admin/img/' . $args['img'] );
            $img_url = JFAQ()->url( 'assets/admin/img/' . $args['img'] );
            if ( file_exists( $img_path ) ) {
                echo sprintf( '<p><img class="joli-admin-image" src="%s"></p>', $img_url );
            }
        }
        //Adds a custom section if any
        if ( isset( $args['custom'] ) && $args['custom'] ) {
            echo sprintf( '<div class="joli-custom">%s</div>', $args['custom'] );
        }
    }

    /**
     * Returns the html tag corresponding to the $args['type']
     * Ex: 'text', 'select','checkbox', etc
     */
    public function displayInput( $args, $data ) {
        $method = 'process' . ucfirst( $args['type'] );
        if ( method_exists( $this, $method ) ) {
            //calls a matching function
            //ex: Call 'processText' for $args['type'] == 'text
            return call_user_func( [$this, $method], $args, $data );
        }
        return;
    }

    private function processPosttype( $args, $data ) {
        $pt_args = [
            'public'   => true,
            '_builtin' => true,
        ];
        $post_types = get_post_types( $pt_args, 'objects' );
        // if (is_array($data['value'])) {
        $enabled = ( $args['pro'] ? ' disabled' : '' );
        $output = "<fieldset{$enabled}>";
        foreach ( $post_types as $post_type => $args ) {
            $checked = false;
            if ( is_array( $data['value'] ) ) {
                $checked = ( in_array( $post_type, $data['value'] ) ? true : false );
            }
            $output .= sprintf(
                '<div class="%s"><input type="checkbox" id="%s[%s]" name="%s[]" value="%s" class="joli-checkbox" %s><label for="%s[%s]">%s</label></div>',
                $data['classes'],
                $data['name'],
                $post_type,
                $data['name'],
                $post_type,
                ( $checked ? 'checked' : '' ),
                $data['name'],
                $post_type,
                $args->label . ' [ ' . $post_type . ' ]'
            );
        }
        $output .= '</fieldset>';
        return $output;
        // }
        return;
    }

    private function processTextarea( $args, $data ) {
        $ta_size = 'cols="100" rows="12"';
        if ( isset( $args['textarea-size'] ) ) {
            switch ( $args['textarea-size'] ) {
                case 'small':
                    $ta_size = 'cols="60" rows="6"';
                    break;
            }
        }
        return sprintf(
            '<textarea class="%s" id="%s" name="%s" %s placeholder="%s"%s>%s</textarea><br>',
            $data['classes'],
            $data['name'],
            $data['name'],
            $ta_size,
            $data['placeholder'],
            ( $args['pro'] ? ' disabled' : '' ),
            esc_textarea( $data['value'] )
        );
    }

    private function processText( $args, $data ) {
        return sprintf(
            '<input type="text" class="%s" id="%s" name="%s" value="%s" placeholder="%s"%s>',
            $data['classes'],
            $data['name'],
            $data['name'],
            esc_attr( $data['value'] ),
            $data['placeholder'],
            ( $args['pro'] ? ' disabled' : '' )
        );
    }

    private function processCheckbox( $args, $data ) {
        $checked = ( isset( $data['value'] ) ? ( $data['value'] == 1 ? true : false ) : false );
        return sprintf(
            '<div class="%s">
            <input type="checkbox" id="%s" class="joli-checkbox" %s data-linkedfield="%s"%s><label for="%s"></label>
            <input type="hidden" id="check_%s" name="%s" value="%d" %s/>
            </div>',
            $data['classes'],
            $data['name'],
            // $data['name'],
            ( $checked ? 'checked' : '' ),
            $data['option'][0] . '-' . $data['option'][1],
            ( $args['pro'] ? ' disabled' : '' ),
            $data['name'],
            $data['option'][0] . '-' . $data['option'][1],
            $data['name'],
            ( $checked ? 1 : 0 ),
            ( $args['pro'] ? ' disabled' : '' )
        );
    }

    private function processSwitch( $args, $data ) {
        $checked = ( isset( $data['value'] ) ? ( $data['value'] == 1 ? true : false ) : false );
        // pre($args);
        $tpl_data = [
            'classes'       => $data['classes'],
            'name'          => $data['name'],
            'checked'       => ( $checked ? ' checked' : '' ),
            'checked_value' => ( $checked ? 1 : 0 ),
            'linkedfield'   => $data['option'][0] . '-' . $data['option'][1],
            'disabled'      => ( $args['pro'] ? ' disabled' : '' ),
            'pro_class'     => ( $args['pro'] ? ' joli-pro' : '' ),
            'deactivates'   => ( isset_or_null( $args['deactivates'] ) ? implode( ',', $args['deactivates'] ) : '' ),
        ];
        $template = '<div class="{{classes}}">
            <label class="joli-switch" for="{{name}}">
                <input type="checkbox" id="{{name}}" class="joli-checkbox"{{checked}} data-linkedfield="{{linkedfield}}" data-deactivates="{{deactivates}}"{{disabled}}>
                <span class="slider round"></span>
            </label>
            <input type="hidden" id="check_{{linkedfield}}" name="{{name}}" value="{{checked_value}}"{{disabled}}/>
        </div>';
        return $this->doTemplate( $template, $tpl_data );
        // return sprintf(
        //     '<div class="%s">
        //         <label class="joli-switch" for="%s">
        //             <input type="checkbox" id="%s" class="joli-checkbox" %s data-linkedfield="%s"%s>
        //             <span class="slider round"></span>
        //         </label>
        //         <input type="hidden" id="check_%s" name="%s" value="%d" %s/>
        //     </div>',
        //     $data['classes'],
        //     $data['name'],
        //     $data['name'],
        //     // $data['name'],
        //     ($checked ? 'checked' : ''),
        //     $data['option'][0] . '-' . $data['option'][1],
        //     $args['pro'] ? ' disabled' : '',
        //     $data['option'][0] . '-' . $data['option'][1],
        //     $data['name'],
        //     $checked ? 1 : 0,
        //     $args['pro'] ? ' disabled' : ''
        // );
    }

    private function processSelect( $args, $data ) {
        $items = $args['values'];
        $items_pro = ( isset( $args['values_pro'] ) ? $args['values_pro'] : [] );
        $output = sprintf(
            '<div class="%s"><select name="%s" id="%s" data-selector="%s"%s>',
            $data['classes'],
            $data['name'],
            $data['name'],
            $data['option'][0] . '-' . $data['option'][1],
            ( $args['pro'] ? ' disabled' : '' )
        );
        foreach ( $items as $id => $name ) {
            $is_pro = ( in_array( $id, $items_pro ) ? true : false );
            $output .= sprintf(
                '<option%s value="%s"%s>%s</option>',
                ( $id == $data['value'] ? ' selected' : '' ),
                $id,
                ( $is_pro ? ' disabled' : '' ),
                ( $is_pro ? $name . ' [PRO]' : $name )
            );
        }
        $output .= '</select></div>';
        if ( isset( $args['media'] ) ) {
            $output .= '<p>';
            foreach ( $args['media'] as $media_id => $media_name ) {
                $output .= sprintf(
                    '<img id="%s" class="joli-admin-image joli-admin-image-%s%s" src="%s">',
                    $data['option'][0] . '-' . $data['option'][1] . '-' . $media_id,
                    $data['option'][0] . '-' . $data['option'][1],
                    ( $media_id !== $data['value'] ? ' hidden' : '' ),
                    JFAQ()->url( 'assets/admin/img/' . $media_name )
                );
            }
            $output .= '</p>';
        }
        return $output;
    }

    private function processRadioicon( $args, $data ) {
        $checked = ( isset( $data['value'] ) ? ( $data['value'] == 1 ? true : false ) : false );
        $items = $args['values'];
        $items_pro = ( isset( $args['values_pro'] ) ? $args['values_pro'] : [] );
        $output = sprintf( '<div class="%s">', $data['classes'] );
        foreach ( $items as $id => $name ) {
            $is_pro = ( in_array( $id, $items_pro ) ? true : false );
            // $output .= sprintf(
            //     '<label class="joli-radio-icon" for="radio_%s">
            //         <input type="radio" id="radio_%s" name="%s" class="joli-radio" value="%s" %s>
            //         <div>%s</div>
            //     </label>',
            //     //label
            //     $data['name'] . $id,
            //     //id
            //     $data['name'] . $id,
            //     //name
            //     $data['name'],
            //     //value
            //     $id,
            //     //selected
            //     $id == $data['value'] ? ' checked' : '',
            //     //name
            //     $name
            // );
            $pro_suffix = ( $is_pro ? '-disabled' : '' );
            $tpl_data = [
                'id'        => $data['name'] . $id,
                'name'      => $data['name'] . $pro_suffix,
                'value'     => $id,
                'checked'   => ( $id == $data['value'] ? ' checked' : '' ),
                'label'     => $name,
                'disabled'  => ( $is_pro ? ' disabled' : '' ),
                'pro_class' => ( $is_pro ? ' joli-pro' : '' ),
            ];
            $template = '<label class="joli-radio-icon{{pro_class}}" for="radio_{{id}}">
                    <input type="radio" id="radio_{{id}}" name="{{name}}" class="joli-radio{{pro_class}}" value="{{value}}"{{checked}}{{disabled}}>
                    <div class="joli-html-label">{{label}}</div>
                </label>';
            $output .= $this->doTemplate( $template, $tpl_data );
        }
        $output .= '</div>';
        return $output;
        return $this->doTemplate( $template, $data );
        return sprintf(
            '<div class="%s">
            <input type="radio" id="%s" class="joli-radio" %s data-linkedfield="%s"><label for="%s"></label>
            <input type="hidden" id="check_%s" name="%s" value="%d" />
            </div>',
            $data['classes'],
            $data['name'],
            // $data['name'],
            ( $checked ? 'checked' : '' ),
            $data['option'][0] . '-' . $data['option'][1],
            $data['name'],
            $data['option'][0] . '-' . $data['option'][1],
            $data['name'],
            ( $checked ? 1 : 0 )
        );
    }

    private function processUnitinput( $args, $data ) {
        $items = $args['values'];
        $items_pro = ( isset( $args['values_pro'] ) ? $args['values_pro'] : [] );
        $value_text = null;
        $value_option = null;
        if ( $data['value'] != '' ) {
            $split_values = explode( '|', $data['value'] );
            if ( $split_values ) {
                $value_text = $split_values[0];
                $value_option = $split_values[1];
            }
        }
        //options
        $options = '';
        foreach ( $items as $id => $name ) {
            $is_pro = ( in_array( $id, $items_pro ) ? true : false );
            $options .= sprintf(
                '<option%s value="%s"%s>%s</option>',
                ( $id == $value_option ? ' selected' : '' ),
                $id,
                ( $is_pro ? ' disabled' : '' ),
                ( $is_pro ? $name . ' [PRO]' : $name )
            );
        }
        $tpl_data = [
            'classes'     => $data['classes'],
            'option'      => $data['option'][0] . '-' . $data['option'][1],
            'name'        => $data['name'],
            'placeholder' => $data['placeholder'],
            'raw_value'   => esc_attr( $data['value'] ),
            'value'       => esc_attr( $value_text ),
            'disabled'    => ( $args['pro'] ? ' disabled' : '' ),
            'options'     => $options,
        ];
        $template = '<fieldset class="joli-css-unit-field"{{disabled}}>
                <label class="{{classes}}" for="{{name}}">
                    <input type="hidden" class="{{classes}}" id="joli-css-unit_{{option}}" name="{{name}}" value="{{raw_value}}">
                    <input type="text" class="joli-css-unit-input" placeholder="{{placeholder}}" data-linkedfield="{{option}}" value="{{value}}">
                    <select class="joli-css-unit-values" data-linkedfield="{{option}}">
                        {{options}}
                    </select>
                </label>
            </fieldset>';
        return $this->doTemplate( $template, $tpl_data );
    }

}
