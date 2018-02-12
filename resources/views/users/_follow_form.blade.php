{{-- 当用户访问的是自己的个人页面时，关注表单不应该被显示出来 --}}
@if($user->id !== Auth::user()->id)
    <div id="follow_form">
        {{-- 当用户已被关注时，显示的是取消关注的按钮 --}}
        {{-- 判断用户是否已被关注 --}}
        @if(Auth::user()->isFollowing($user->id))
            <form action="{{ route('followers.destroy',$user->id) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-sm">取消关注</button>
            </form>
        @else
            {{-- 未被关注时，使用的则是关注按钮 --}}
            <form action="{{ route('followers.store',$user->id) }}" method="POST">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-sm btn-primary">关注</button>
            </form>
        @endif
    </div>
@endif