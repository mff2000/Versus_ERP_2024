
@extends('layouts.app')

@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>
            Configurações <small>Configurações do Sistema</small>
        </h3>
    </div>

    <div class="title_right">
      
      <div class="pull-right">

      </div>

    </div>
</div>


<div class="row">

  <div class="col-md-12 col-sm-12 col-xs-12">

    @include('flash::message')
    
    <div class="x_panel">
     
      <div class="x_content">

        <div class="" role="tabpanel" data-example-id="togglable-tabs">
          <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
            <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Empresa</a>
            </li>
            <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Conf 1</a>
            </li>
            <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Conf 2</a>
            </li>
          </ul>
          <div id="myTabContent" class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
              
               @include('cadastros/config/empresa')

            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
              <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo
                booth letterpress, commodo enim craft beer mlkshk aliquip</p>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
              <p>xxFood truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo
                booth letterpress, commodo enim craft beer mlkshk </p>
            </div>
          </div>
        </div>

      </div>
  </div>
</div>

</div><!-- #row -->

@endsection