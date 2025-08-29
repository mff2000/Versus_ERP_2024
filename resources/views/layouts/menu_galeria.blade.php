<li>
    <a><i class="fa fa-paint-brush"></i> Galeria <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li>
            <a href="{{ url('/galeria/obra?session=clear') }}">Cadastros de Obras</a>
        </li>
        <li>
            <a href="{{ url('galeria/tipo_obra?session=clear') }}">Cadastros de Tipos de Obras</a>
        </li>
        <li>
            <a href="{{ url('/galeria/tecnica?session=clear') }}">Cadastro de Técnicas</a>
        </li>
        <li>
            <a href="{{ url('/galeria/venda?session=clear') }}">Vendas</a>
        </li>
        <li>
            <a href="{{ url('/galeria/consignacao?session=clear') }}">Consignações</a>
        </li>
    </ul>
</li>