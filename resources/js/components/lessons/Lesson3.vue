<template>
    <div class="container h-100">
      <div class="row justify-content-center h-100 align-items-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="span-header">Lesson 3</span>
                <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
              </div>
              <div class="question-header">電卓を作成してください。</div>
              <div class="calculator-container" id="app">
                <table class="table table-bordered">
                  <tr>
                    <td colspan="3">
                        <input class="form-control form-control-lg text-right" type="text" id="output" v-model="result" @keyup.enter="calculate('=')" value="0" readonly>
                    </td>
                    <td>
                      <button class="btn btn-secondary btn-lg" @click="calculate('c')">C</button>
                    </td>
                  </tr>
                  <tr v-for="row in items" :key="row.id">
                    <td v-for="column in row" :key="column.id">
                      <button class="btn btn-outline-secondary btn-lg" v-on:click="calculate(column)">{{ column }}</button>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  <script>
  export default {
    data() {
      return {
        result: 0,
        items: [
          ['7', '8', '9', '/'],
          ['4', '5', '6', '*'],
          ['1', '2', '3', '-'],
          ['0', '.', '+', '='],
        ]
      }
    },
    methods: {
      onBack() {
        this.$router.push({ name: 'home' })
      },
      calculate(cmd) {
        if (cmd == '=') {
          this.result = eval(this.result);
        } else if (cmd == 'c') {
          this.result = '0';
        } else if (this.result == '0') {
          this.result = cmd;
        } else {
          this.result += cmd;
        }
      }
    },
  }
  </script>
  <style scoped>
  .calculator-container {
    height: 100%;
  }

  .table {
    margin: 0 auto;
  }

  .input[type="text"] {
    border: none;
    outline: none;
  }

  .btn {
    font-size: 1.5em;
  }

  .btn-lg {
    width: 100%;
    height: 100%;
  }

  .btn-secondary {
    background-color: #eee;
    border-color: #ccc;
  }

  .btn-outline-secondary {
    background-color: #fff;
    border-color: #ccc;
  }

  .btn:hover {
    background-color: #ddd;
    cursor: pointer;
  }

  .form-control-lg {
    font-size: 200%;
  }

  .text-right {
    text-align: right;
  }
  </style>
