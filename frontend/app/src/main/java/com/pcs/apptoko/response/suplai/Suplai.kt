package com.pcs.apptoko.response.suplai

import android.os.Parcelable
import kotlinx.parcelize.Parcelize


@Parcelize
data class Suplai(
    var id_produk : String,
    var harga : String,
    var id_distributor : String,
    var jumlah : String
) : Parcelable
