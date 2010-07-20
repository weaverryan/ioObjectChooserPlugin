ioObjectChooserPlugin
=====================

Iostudio object chooser plugin
------------------------------

installation instructions

* You have to have jQuery installed and available on the new & edit views of
your form.

http://www.jquery.com

* Choose a relationship you wish to transform into a "chooser" (many-to-one or
many-to-many).  Change the form widget that represents this relationship on
the form object itself to one of the two widgets included here.

i.e. for a new Product form with one Category..

    // .. in form::configure()
    // use either ioObjectChooserWidget or ioObjectChooserManyWidget
    $this->widgetSchema['category_id'] = new ioObjectChooserWidget( ... );


* For all widgets you will be required to provide two options.  One is the
"form_object" which is just the object the form is bound to.  The other is the
"relation_name" or the proper Doctrine alias of the set of related objects.

i.e. for a new Product form with many Categories, if the chooser is to choose
from Categories you must specify "Category" as the "relation_name" option and
the "Product" object as the "form_object"
    
    // in form::configure()
    $this->widgetSchema['categories_list'] = new ioObjectChooserManyWidget(
      array(
        'form_object' => $this->getObject(),
        'relation_name' => 'Categories'
        )
      );
