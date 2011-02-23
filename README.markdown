ioObjectChooserPlugin
=====================

Iostudio object chooser plugin
------------------------------

installation instructions

* You have to have jQuery installed and available on the new & edit views of
your form.

see: http://www.jquery.com

* Choose a relationship you wish to transform into a "chooser" (many-to-one or
many-to-many).  Change the form widget that represents this relationship on
the form object itself to one of the two widgets included here.

i.e. for a new Product form with one Category..

    // .. in form::configure()
    // use either ioObjectChooserWidget or ioObjectChooserManyWidget
    $this->widgetSchema['category_id'] = new ioObjectChooserWidget( ... );


* For all widgets all you must provide is the 'model' option

i.e. for a new Product form with many Categories, if the chooser is to choose
from Categories you must specify "Category" as the "model" option
    
    // in form::configure()
    $this->widgetSchema['categories_list'] = new ioObjectChooserManyWidget(
      array(
        'model' => 'classOfRelatedModel'
        )
      );


you can add filtering/searching by enabling it in app.yml (default is disabled)

    all:
      io_object_chooser:
        filter:
          default:
            enable: false
          ModelName:
            enable: true
            fields:
              - title
              - description


Each object will be output by calling __toString() on the object by default.
To customize how each object is "shown" in the widget, put something like this
in the app.yml config:

    all:
      io_object_chooser:
        render:
          ModelName:
            show_partial: 'mooduleName/partialName'
            show_method: someMethodOnModelObjects

