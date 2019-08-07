<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />


<div id="space-books">
    @if($share)
    <ul>
    @foreach($share as $index => $entity)
        @include('space.list-space-left-share-list-item', ['entity'=>$entity])
    @endforeach
    </ul>
    @endif
</div>

<!--<div id="space-books">-->
<!--    <ul>-->
<!--        <li data-jstree='{"icon":"{{URL::asset("assets/imgs/users.png")}}"}'>space 1-->
<!--            <ul>-->
<!--                <li data-jstree='{"icon":"{{URL::asset("assets/imgs/book.png")}}"}'>book 1-->
<!--                    <ul>-->
<!--                        <li>chapter 1-->
<!--                            <ul>-->
<!--                                <li data-jstree='{"icon":"{{URL::asset("assets/imgs/file.png")}}"}'>-->
<!--                                    <a href="#">page 1</a>-->
<!--                                </li>-->
<!--                                <li data-jstree='{"icon":"{{URL::asset("assets/imgs/file_reading.png")}}"}'>-->
<!--                                    <a href="#">page 2</a>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--            </ul>-->
<!--        </li>-->
<!--    </ul>-->
<!--</div>-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<script>

        $('#space-books').jstree({
            'plugins':["wholerow"],
        });

</script>
