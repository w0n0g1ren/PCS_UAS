package com.pcs.apptoko.response.suplai

data class SuplaiResponsePost(
    val `data`: DataSuplai,
    val message: String,
    val success: Boolean
)

data class DataSuplai (
    val `suplai`: Suplai
)

