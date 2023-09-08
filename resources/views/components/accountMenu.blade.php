<ul>
    <li @if(!empty($select) && $select == 'account') class="active"@endif ><a href="{{route('account')}}">Профиль</a></li>
    <li @if(!empty($select) && $select == 'profile') class="active"@endif ><a href="{{route('account.profile')}}">Мои профили</a></li>
    <li @if(!empty($select) && $select == 'order') class="active"@endif ><a href="{{route('account.order')}}">Мои заказы</a></li>
    <li><a href="#">Избранное</a></li>
    <li class="mt-4"><a href="{{route('exit')}}">Выйти</a></li>
</ul>
