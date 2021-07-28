<style>
    .flex-parent {
        border: solid 1px;
        width: 800px;
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
        border: solid 1px red;
        box-sizing: border-box;
        flex-basis: 100px;
        height: 100px;
        align-items: center;
        justify-content: center;
        /*flex-grow: 1;*/
        flex-shrink: 1;
        /*flex-basis: auto;*/
        /*flex: none | [ <'flex-grow'> <'flex-shrink'>? || <'flex-basis'> ]*/
        /*align-self: auto | flex-start | flex-end | center | baseline | stretch;*/
    }
</style>
<div class="flex-parent">
  <div class="my-flex-children" >
    1
  </div>
  <div class="my-flex-children" style="order: 12">
    2
  </div>
  <div class="my-flex-children">
    3
  </div>
  <div class="my-flex-children">
    4
  </div>
  <div class="my-flex-children">
    5
  </div>
  <div class="my-flex-children">
    6
  </div>
  <div class="my-flex-children" style="order: 3; flex-basis: 300px">
    7
  </div>
  <div class="my-flex-children" style="order: 2; flex-basis: 200px">
    8
  </div>
  <div class="my-flex-children" style="order: 1;flex-basis: 100px" >
    9
  </div>
</div>
