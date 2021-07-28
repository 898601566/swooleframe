<style>
    .flex-parent {
        border: solid 1px;
        width: 300px;
        height: 330px;
        display: flex;
        /*flex | inline-flex; */
        flex-direction: row;
        /*row | row-reverse | column | column-reverse;*/
        flex-wrap: wrap;
        /*nowrap | wrap | wrap-reverse;*/
        justify-content: center;
        /*flex-start | flex-end | center | space-between | space-around;*/
        align-items: flex-end
        /*flex-start | flex-end | center | baseline | stretch;*/
        /*针对行*/
        /*align-content: flex-end*/
        /*flex-start | flex-end | center | space-between | space-around | stretch;*/
    }
    .my-flex-children {
        display: flex;
        border: solid 1px;
        flex-direction: column;
        width: 100px;
        height: 100px;
        padding: 15px;
        box-sizing: border-box;
        /*flex-grow: 0;*/
        /*收缩*/
        /*flex-shrink: 1;*/
        /*flex-basis: auto;*/
        /*flex: none | [ <'flex-grow'> <'flex-shrink'>? || <'flex-basis'> ]*/
        /*align-self: auto | flex-start | flex-end | center | baseline | stretch;*/
        overflow: hidden;
    }
    .text{
        /*省略号*/
        overflow: hidden;
        white-space:pre-wrap;
        text-overflow: ellipsis;
    }
</style>
<div class="flex-parent">
  <div class="my-flex-children">
    <p class="text">11111111111111111111111111111111111</p>
  </div>
  <div class="my-flex-children" style="order: 12">
    22222222222222222222222222222222222
  </div>
  <div class="my-flex-children">
    33333333333333333333333333333333333
  </div>
  <div class="my-flex-children">
    44444444444444444444444444444444444
  </div>
  <div class="my-flex-children">
    55555555555555555555555555555555555
  </div>
  <div class="my-flex-children">
    6
  </div>
  <div class="my-flex-children">
    7
  </div>
  <div class="my-flex-children" style="order: 2">
    8
  </div>
  <div class="my-flex-children" style="order: 1">
    9
  </div>
</div>
