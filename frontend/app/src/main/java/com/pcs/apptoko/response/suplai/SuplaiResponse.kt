package com.pcs.apptoko.response.suplai

import com.pcs.apptoko.response.produk.Data

data class SuplaiResponse(
    val `data`: Data,
    val message: String,
    val success: Boolean

)
