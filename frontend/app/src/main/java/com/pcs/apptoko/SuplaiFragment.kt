package com.pcs.apptoko

import android.annotation.SuppressLint
import android.os.Bundle
import android.util.Log
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.EditText
import android.widget.TextView
import android.widget.Toast
import androidx.navigation.fragment.findNavController
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.google.android.material.textfield.TextInputEditText
import com.pcs.apptoko.adapter.ProdukAdapter
import com.pcs.apptoko.api.BaseRetrofit
import com.pcs.apptoko.response.produk.Produk
import com.pcs.apptoko.response.produk.ProdukResponse
import com.pcs.apptoko.response.produk.ProdukResponsePost
import com.pcs.apptoko.response.suplai.Suplai
import com.pcs.apptoko.response.suplai.SuplaiResponsePost
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response


class SuplaiFragment : Fragment() {
    private val api by lazy { BaseRetrofit().endpoint }


    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

    }

    @SuppressLint("MissingInflatedId")
    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        val view = inflater.inflate(R.layout.fragment_suplai, container, false)

        val btnProsesProduk = view.findViewById<Button>(R.id.btnProsesProduk)

        val id_produk = view.findViewById<TextView>(R.id.id_produk_suplai)
        val harga = view.findViewById<TextView>(R.id.harga_suplai)
        val jumlah = view.findViewById<TextView>(R.id.jumlah_suplai)
        val id_distributor = view.findViewById<TextView>(R.id.id_suplaier_suplai)

        val status = arguments?.getString("status")
        val suplai = arguments?.getParcelable<Suplai>("produk")

        Log.d("suplaiForm",suplai.toString())

        if(status=="edit"){
            id_produk.setText(suplai?.id_produk.toString())
            harga.setText(suplai?.harga.toString())
            jumlah.setText(suplai?.jumlah.toString())
            id_distributor.setText(suplai?.id_distributor.toString())
        }

        btnProsesProduk.setOnClickListener{
            val id_produk = view.findViewById<EditText>(R.id.id_produk_suplai)
            val harga = view.findViewById<EditText>(R.id.harga_suplai)
            val jumlah = view.findViewById<EditText>(R.id.jumlah_suplai)
            val id_distributor = view.findViewById<EditText>(R.id.id_suplaier_suplai)

            val token = LoginActivity.sessionManager.getString("TOKEN")
            val adminId = LoginActivity.sessionManager.getString("ADMIN_ID")

            if(status=="edit"){
                api.putSuplai(token.toString(),id_produk.text.toString().toInt(),harga.text.toString().toInt(),jumlah.text.toString().toInt(),id_distributor.text.toString().toInt()).enqueue(object :
                    Callback<SuplaiResponsePost> {
                    override fun onResponse(
                        call: Call<SuplaiResponsePost>,
                        response: Response<SuplaiResponsePost>
                    ) {
                        Log.d("ResponData",response.body()!!.data.toString())
                        Toast.makeText(activity?.applicationContext,"Data "+ response.body()!! +" di edit",Toast.LENGTH_LONG).show()

                        findNavController().navigate(R.id.produkFragment)
                    }

                    override fun onFailure(call: Call<SuplaiResponsePost>, t: Throwable) {
                        Log.e("Error",t.toString())
                    }

                })
            } else{
                api.postSuplai(token.toString(),id_produk.text.toString().toInt(),harga.text.toString().toInt(),jumlah.text.toString().toInt(),id_distributor.text.toString().toInt()).enqueue(object :
                    Callback<SuplaiResponsePost> {
                    override fun onResponse(
                        call: Call<SuplaiResponsePost>,
                        response: Response<SuplaiResponsePost>
                    ) {
                        Log.d("Data",response.toString())
                        Toast.makeText(activity?.applicationContext,"Data di input", Toast.LENGTH_LONG).show()

                        findNavController().navigate(R.id.suplaiFragment)
                    }

                    override fun onFailure(call: Call<SuplaiResponsePost>, t: Throwable) {
                        Log.e("Error",t.toString())
                    }

                })
            }
            }
        return view
        }
    }

