<?php

class Newsletter_Widget extends WP_Widget {
	function Newsletter_Widget() {
                parent::WP_Widget(false, $name = 'Newsletter');
	}

	function widget($args, $instance) {
                $options = get_option('newsletter');

                // $args is an array of strings that help widgets to conform to
                // the active theme: before_widget, before_title, after_widget,
                // and after_title are the array keys. Default tags: li and h2.
                extract($args);

                // Each widget can store its own options. We keep strings here.
                $title = apply_filters('widget_title', $instance['title']);
                $text = $instance['text'];
                $form = $instance['form'];

                // These lines generate our output. Widgets can be very complex
                // but as you can see here, they can also be very, very simple.
                echo $before_widget . $before_title . $title . $after_title;

                if (newsletter_has_extras('1.0.2') && $form != '') {
                        $buffer .= str_replace('{newsletter_url}', $options['url'], newsletter_extras_get_form($form));
                }
                else {
                        if (isset($options['noname'])) {
                                $buffer = str_replace('{newsletter_url}', $options['url'], newsletter_label('widget_form_noname'));
                        }
                        else {
                                $buffer = str_replace('{newsletter_url}', $options['url'], newsletter_label('widget_form'));
                        }
                }
                $buffer = str_replace('{text}', $instance['text'], $buffer);
                $buffer = str_replace('{count}', newsletter_subscribers_count(), $buffer);

                //if (defined('NEWSLETTER_EXTRAS')) echo $buffer;
                //else echo $buffer . '<div style="text-align:right;padding:0 10px;margin:0;"><a style="font-size:9px;color:#bbb;text-decoration:none" href="http://www.satollo.net">by satollo.net</a></div>';

                echo $buffer;

                echo $after_widget;
	}

	function form($instance) {
                $title = esc_attr($instance['title']);
                $text = esc_attr($instance['text']);
                $form = esc_attr($instance['form']);
                // Here is our little form segment. Notice that we don't need a
                // complete form. This will be embedded into the existing form.
                ?>
                <p>
                  <label for="<?php echo $this->get_field_id('title'); ?>">Title</label><br />
                  <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
                </p>
                <p>
                  <label for="<?php echo $this->get_field_id('text'); ?>">Introduction</label><br />
                  <textarea id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
                </p>
                <?php if (newsletter_has_extras('1.0.2')): ?>
                  <p>
                    <label for="<?php echo $this->get_field_id('form'); ?>">Form number</label><br />
                    <input id="<?php echo $this->get_field_id('form'); ?>" name="<?php echo $this->get_field_name('form'); ?>" type="text" value="<?php echo $form; ?>" />
                  </p>
                <?php endif; ?>
                <?php
	}

	function update($new_instance, $old_instance) {
		// processes widget options to be saved
                $instance = $old_instance;
                $instance['title'] = strip_tags($new_instance['title']);
                $instance['text'] = strip_tags($new_instance['text']);
                $instance['form'] = strip_tags($new_instance['form']);
                return $instance;
        }

}

add_action('widgets_init', create_function('', 'return register_widget("Newsletter_Widget");'));
