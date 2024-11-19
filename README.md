# Math-calculator
一个由PHP驱动的简单的数学运算器(未来考虑使用BC高精度库)



#### 展望未来

- 实现高精度数学运算器
- 实现兼容解析 markdown 数学语法
- 实现自然语言处理[由NLP模型提供支持] 数学问题

#### 原理层

- 词法核心由 `单Token扫描` 实现

- 语法核心由 `AST抽象语法树` 实现

#### 实现的功能:

- [x] 基础四则运算
- [x] 小数计算
- [x] 乘方
- [x] 求余
- [ ] 括号运算
- [ ] 完整错误控制 (目前大部分情况都已支持)

#### 即将开发的内容:

1. `Hook` 式开发：注入"运算符" (实现自定义运算功能)
2. 中间过程接口生成: 通过保存AST树来实现更快的运算

