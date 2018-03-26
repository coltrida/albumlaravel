<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use View;

class PageController extends Controller
{
    protected $data = [
        [
            'name'=>'davide',
            'lastname'=>'colt',
        ],
        [
            'name'=>'miao',
            'lastname'=>'bau',
        ]

    ];

    public function about()
    {
       return view('about');

  //      return View::make('about');

//        $view = app('view');
//        return $view('about');
    }

    public function blog()
    {
        //return view('blog');
        return view('blog',
            ['img_url' => 'http://lorempixel.com/g/400/200/',
                'img_title' => 'immagine inclusa',
                'slot' => ''

        ]);
    }

    public function staff()
    {
/*        return view('staff',                   //PRIMO MODO PER PASSARE I DATI ALLA VIEW
            [
                'title' => 'Our staff',
                'staff' => $this->data
            ]
        );*/

/*        return view('staff')                  //SECONDO MODO CON ELOQUENT
            ->with('staff', $this->data)        //CHIAVE, VALORE
            ->with('title', 'Our Staffe');*/

/*        return view('staff')                  //TERZO MODO CON ELOQUENT CON METODO MAGICO
            ->withStaff($this->data)
            ->withTitle('Our Staffa');*/

/*        $staff = $this->data;                 //QUARTO MODO CON COMPACT = crea un array le cui chiavi sono title e staff
        $title = 'OUR STAFFU';
        return view('staff', compact('title','staff'));*/

        $staff = $this->data;
        $title = 'OUR STAFFBLADE';
        return view('staffb', compact('title','staff'));

    }
}
